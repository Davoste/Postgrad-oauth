<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'email' => 'test@example.com',
            'email_confirmation' => 'test@example.com',
            'id_no' => '30123456',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'surname' => 'Doe',
            'first_name' => 'Jane',
        ]);
    }

    public function test_email_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'email' => 'test@example.com',
            'email_confirmation' => 'different@example.com',
            'id_no' => '30123456',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email_confirmation');
        $this->assertGuest();
    }
}
