<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder de base pour tous les tests
        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\AuthProviderSeeder::class,
        ]);
    }

    /**
     * Créer un utilisateur avec le rôle spécifié et retourner [user, token]
     */
    protected function createUserWithRole(string $role = 'user'): array
    {
        $roleId = match ($role) {
            'admin'    => 1,
            'employee' => 2,
            default    => 3,
        };

        $user = \App\Models\User::factory()->create(['role_id' => $roleId]);
        $token = $user->createToken('test')->plainTextToken;

        return [$user, $token];
    }

    protected function authHeaders(string $token): array
    {
        return [
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ];
    }
}