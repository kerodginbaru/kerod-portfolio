<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_with_correct_credentials(): void
    {
        $admin = User::factory()->create(['password' => Hash::make('correct-password-1')]);

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'correct-password-1',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $admin = User::factory()->create(['password' => Hash::make('correct-password-1')]);

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_there_is_no_public_registration_endpoint(): void
    {
        $this->postJson('/api/admin/register', [
            'name' => 'Attempted Admin',
            'email' => 'attacker@example.com',
            'password' => 'whatever',
        ])->assertNotFound();
    }

    public function test_authenticated_admin_can_fetch_profile_and_logout(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/me')
            ->assertOk()
            ->assertJsonPath('data.email', $admin->email);
    }

    public function test_password_change_requires_current_password(): void
    {
        $admin = User::factory()->create(['password' => Hash::make('old-password-1')]);

        $response = $this->actingAs($admin, 'sanctum')->putJson('/api/admin/password', [
            'current_password' => 'wrong-current-password',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('current_password');
    }
}
