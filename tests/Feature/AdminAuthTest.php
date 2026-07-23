<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_access_panel(): void
    {
        $admin = User::factory()->create(['password' => bcrypt('secret-password')]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')->assertStatus(200);
    }

    public function test_public_dashboard_remains_accessible_without_login(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $admin = User::factory()->create(['password' => bcrypt('secret-password')]);

        $response = $this->from('/login')->post('/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
