<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // ─── REGISTER ──────────────────────────────────────────────────────────────

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email'                 => 'nouveau@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
            'birth_date'            => '1995-01-01',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['user' => ['id', 'email'], 'token'],
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'nouveau@example.com']);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'email'                 => 'existing@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }

    public function test_register_fails_with_weak_password(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email'                 => 'test@example.com',
            'password'              => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_fails_if_underage(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email'                 => 'young@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
            'birth_date'            => now()->subYears(16)->toDateString(),
        ]);

        $response->assertStatus(422);
    }

    // ─── LOGIN ─────────────────────────────────────────────────────────────────

    public function test_user_can_login_with_correct_credentials(): void
    {
        [$user] = $this->createUserWithRole('user');

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        [$user] = $this->createUserWithRole('user');

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('success', false);
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(404);
    }

    // ─── ME & LOGOUT ───────────────────────────────────────────────────────────

    public function test_authenticated_user_can_get_their_profile(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        $response = $this->getJson('/api/auth/me', $this->authHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonPath('data.email', $user->email);
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    public function test_user_can_logout(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        $response = $this->postJson('/api/auth/logout', [], $this->authHeaders($token));

        $response->assertStatus(200);

        // Le token ne doit plus fonctionner après logout
        $this->getJson('/api/auth/me', $this->authHeaders($token))
             ->assertStatus(401);
    }
}