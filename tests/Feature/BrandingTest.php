<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
    }

    public function test_brand_helper_falls_back_to_config_defaults()
    {
        $this->assertEquals(config('brand.short_name'), brand('short_name'));
    }

    public function test_brand_helper_prefers_db_setting_over_config()
    {
        Setting::set('brand.short_name', 'Acme University', 'brand');

        $this->assertEquals('Acme University', brand('short_name'));
        $this->assertNotEquals(config('brand.short_name'), brand('short_name'));
    }

    public function test_public_welcome_page_reflects_custom_branding()
    {
        Setting::set('brand.short_name', 'Acme University', 'brand');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Acme University');
    }

    public function test_admin_can_update_branding()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->put('/admin/branding', [
            'name' => 'Acme University Portal',
            'short_name' => 'Acme',
            'tagline' => 'Learn Boldly',
            'favicon_emoji' => '🎓',
            'primary_color' => '#112233',
            'accent_color' => '#445566',
            'gold_color' => '#778899',
            'support_email' => 'help@acme.edu',
            'support_phone' => '000-111',
            'address' => 'Acme City',
            'footer_text' => 'Acme University. All rights reserved.',
        ]);

        $response->assertRedirect(route('admin.branding.edit'));
        $this->assertEquals('#112233', brand('primary_color'));
        $this->assertEquals('Acme', brand('short_name'));
    }

    public function test_non_admin_cannot_access_branding_settings()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get('/admin/branding');
        $response->assertStatus(403);
    }

    public function test_reset_clears_overrides()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Setting::set('brand.short_name', 'Acme', 'brand');

        $this->actingAs($admin)->delete('/admin/branding');

        $this->assertEquals(config('brand.short_name'), brand('short_name'));
    }
}
