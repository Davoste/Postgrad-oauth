<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandAssetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
        Storage::fake('public');
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        return $admin;
    }

    public function test_uploaded_logo_is_served_without_relying_on_storage_symlink()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->put('/admin/branding', [
            'name' => 'Acme', 'short_name' => 'Acme', 'tagline' => 't',
            'favicon_emoji' => '🎓',
            'primary_color' => '#112233', 'accent_color' => '#445566', 'gold_color' => '#778899',
            'support_email' => 'a@b.com', 'support_phone' => '1', 'address' => 'x', 'footer_text' => 'y',
            'logo' => UploadedFile::fake()->image('logo.png', 100, 100),
        ]);
        $response->assertRedirect();

        $logoUrl = brand('logo_url');
        $this->assertStringContainsString('/brand-asset/', $logoUrl);

        // Fetch it exactly like a browser (or Moodle, for the issuer's Logo
        // URL field) would — this must work with NO symlink present, which
        // is exactly what public/storage normally requires.
        $path = parse_url($logoUrl, PHP_URL_PATH);
        $fileResponse = $this->get($path);
        $fileResponse->assertStatus(200);
        $fileResponse->assertHeader('content-type', 'image/png');
    }

    public function test_hero_image_url_is_null_until_uploaded_then_resolves()
    {
        $this->assertNull(brand('hero_image_url'));

        $admin = $this->admin();
        $this->actingAs($admin)->put('/admin/branding', [
            'name' => 'Acme', 'short_name' => 'Acme', 'tagline' => 't',
            'favicon_emoji' => '🎓',
            'primary_color' => '#112233', 'accent_color' => '#445566', 'gold_color' => '#778899',
            'support_email' => 'a@b.com', 'support_phone' => '1', 'address' => 'x', 'footer_text' => 'y',
            'hero_image' => UploadedFile::fake()->image('hero.jpg', 1200, 600),
        ]);

        $heroUrl = brand('hero_image_url');
        $this->assertNotNull($heroUrl);

        $path = parse_url($heroUrl, PHP_URL_PATH);
        $this->get($path)->assertStatus(200);

        // The public homepage should now embed it as a CSS background.
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee($path, false);
    }

    public function test_removing_hero_image_falls_back_to_gradient()
    {
        $admin = $this->admin();
        $this->actingAs($admin)->put('/admin/branding', array_merge($this->baseFields(), [
            'hero_image' => UploadedFile::fake()->image('hero.jpg'),
        ]));
        $this->assertNotNull(brand('hero_image_url'));

        $this->actingAs($admin)->put('/admin/branding', array_merge($this->baseFields(), [
            'remove_hero_image' => '1',
        ]));

        $this->assertNull(brand('hero_image_url'));
    }

    public function test_path_traversal_is_blocked()
    {
        $response = $this->get('/brand-asset/' . urlencode('../../.env'));
        $response->assertStatus(404);
    }

    public function test_nonexistent_asset_returns_404()
    {
        $this->get('/brand-asset/does-not-exist.png')->assertStatus(404);
    }

    public function test_openid_discovery_document_exposes_correct_endpoints()
    {
        $response = $this->getJson('/.well-known/openid-configuration');
        $response->assertStatus(200);
        $response->assertJson([
            'authorization_endpoint' => route('oauth.authorize'),
            'token_endpoint'         => route('oauth.token'),
            'userinfo_endpoint'      => route('oauth.userinfo'),
        ]);
        $response->assertJsonStructure(['issuer', 'scopes_supported', 'grant_types_supported']);
    }

    private function baseFields(): array
    {
        return [
            'name' => 'Acme', 'short_name' => 'Acme', 'tagline' => 't',
            'favicon_emoji' => '🎓',
            'primary_color' => '#112233', 'accent_color' => '#445566', 'gold_color' => '#778899',
            'support_email' => 'a@b.com', 'support_phone' => '1', 'address' => 'x', 'footer_text' => 'y',
        ];
    }
}
