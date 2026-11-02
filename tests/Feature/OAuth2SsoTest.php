<?php

namespace Tests\Feature;

use App\Models\OAuth\OAuthAccessToken;
use App\Models\OAuth\OAuthClient;
use App\Models\OAuth\OAuthRefreshToken;
use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OAuth2SsoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
    }

    private function makeClient(bool $trusted = false): array
    {
        return OAuthClient::generate('Moodle LMS', "https://lms.example.edu/callback\nhttps://lms.example.edu/other", $trusted);
    }

    public function test_unauthenticated_visitor_is_sent_to_login_first()
    {
        [$client] = $this->makeClient();

        $response = $this->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
            'state' => 'xyz',
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_untrusted_client_shows_consent_screen()
    {
        [$client] = $this->makeClient(trusted: false);
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
            'state' => 'xyz',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Moodle LMS');
        $response->assertSee('Allow');
    }

    public function test_trusted_client_skips_consent_and_redirects_with_code()
    {
        [$client] = $this->makeClient(trusted: true);
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
            'state' => 'xyz',
        ]));

        $response->assertStatus(302);
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://lms.example.edu/callback?', $location);
        $this->assertStringContainsString('code=', $location);
        $this->assertStringContainsString('state=xyz', $location);
    }

    public function test_full_authorization_code_exchange_returns_working_access_token()
    {
        [$client, $secret] = $this->makeClient(trusted: true);
        $user = User::factory()->create(['email' => 'grace@example.com']);
        $user->assignRole('student');

        $authResponse = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
            'state' => 'abc',
        ]));

        parse_str(parse_url($authResponse->headers->get('Location'), PHP_URL_QUERY), $params);
        $this->assertArrayHasKey('code', $params);

        // Simulate Moodle's server-to-server token exchange (no session, no CSRF).
        $tokenResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $params['code'],
            'redirect_uri' => 'https://lms.example.edu/callback',
            'client_id' => $client->client_id,
            'client_secret' => $secret,
        ]);

        $tokenResponse->assertStatus(200);
        $tokenResponse->assertJsonStructure(['access_token', 'refresh_token', 'token_type', 'expires_in']);

        // The code must be single-use.
        $replay = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $params['code'],
            'redirect_uri' => 'https://lms.example.edu/callback',
            'client_id' => $client->client_id,
            'client_secret' => $secret,
        ]);
        $replay->assertStatus(400);

        // The access token then resolves user identity for Moodle's userinfo call.
        $accessToken = $tokenResponse->json('access_token');
        $userInfo = $this->getJson('/oauth/userinfo', ['Authorization' => "Bearer {$accessToken}"]);
        $userInfo->assertStatus(200);
        $userInfo->assertJson(['email' => 'grace@example.com']);

        return [$client, $secret, $tokenResponse->json('refresh_token')];
    }

    public function test_wrong_client_secret_is_rejected()
    {
        [$client] = $this->makeClient(trusted: true);
        $user = User::factory()->create();
        $user->assignRole('student');

        $authResponse = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code', 'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
        ]));
        parse_str(parse_url($authResponse->headers->get('Location'), PHP_URL_QUERY), $params);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $params['code'],
            'redirect_uri' => 'https://lms.example.edu/callback',
            'client_id' => $client->client_id,
            'client_secret' => 'totally-wrong',
        ]);

        $response->assertStatus(401);
    }

    public function test_refresh_token_rotates_and_issues_new_access_token()
    {
        [$client, $secret] = $this->makeClient(trusted: true);
        $user = User::factory()->create();
        $user->assignRole('student');

        $authResponse = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code', 'client_id' => $client->client_id,
            'redirect_uri' => 'https://lms.example.edu/callback',
        ]));
        parse_str(parse_url($authResponse->headers->get('Location'), PHP_URL_QUERY), $params);

        $tokenResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code', 'code' => $params['code'],
            'redirect_uri' => 'https://lms.example.edu/callback',
            'client_id' => $client->client_id, 'client_secret' => $secret,
        ]);
        $refreshToken = $tokenResponse->json('refresh_token');
        $oldAccessToken = $tokenResponse->json('access_token');

        $refreshResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'refresh_token', 'refresh_token' => $refreshToken,
            'client_id' => $client->client_id, 'client_secret' => $secret,
        ]);
        $refreshResponse->assertStatus(200);
        $newAccessToken = $refreshResponse->json('access_token');
        $this->assertNotEquals($oldAccessToken, $newAccessToken);

        // Old access token should now be revoked.
        $this->assertNull(OAuthAccessToken::findValidByPlainToken($oldAccessToken));
        $this->assertNotNull(OAuthAccessToken::findValidByPlainToken($newAccessToken));

        // Old refresh token should be revoked too (rotation).
        $this->assertNull(OAuthRefreshToken::findValidByPlainToken($refreshToken));
    }

    public function test_redirect_uri_not_registered_is_rejected()
    {
        [$client] = $this->makeClient();
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code', 'client_id' => $client->client_id,
            'redirect_uri' => 'https://evil.example.com/callback',
        ]));

        $response->assertStatus(400);
    }

    public function test_admin_can_manage_clients()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/admin/sso', [
            'name' => 'Moodle LMS',
            'redirect_uri' => 'https://lms.example.edu/admin/oauth2callback.php',
            'trusted' => '1',
        ]);

        $response->assertRedirect(route('admin.oauth-clients.index'));
        $this->assertDatabaseHas('oauth_clients', ['name' => 'Moodle LMS', 'trusted' => true]);
    }

    public function test_non_admin_cannot_manage_clients()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($student)->get('/admin/sso')->assertStatus(403);
    }
}
