<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Models\AuthProvider;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Inscription classique (email + mot de passe)
     */
    public function register(array $data): array
    {
        $user = User::create([
            'id'            => Str::uuid(),
            'email'         => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'birth_date'    => $data['birth_date'] ?? null,
            'role_id'       => 3, // user par défaut
        ]);

        // Créer l'entrée UserAuth pour "local"
        UserAuth::create([
            'id'               => Str::uuid(),
            'user_id'          => $user->id,
            'provider_id'      => AuthProvider::LOCAL,
            'provider_user_id' => $user->id, // self-ref pour local
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Connexion email + mot de passe
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw AuthException::accountNotFound();
        }

        if (!Hash::check($password, $user->password_hash)) {
            throw AuthException::invalidCredentials();
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return ['user' => $user->load('role'), 'token' => $token];
    }

    /**
     * Connexion / inscription via OAuth (Google, Facebook...)
     */
    public function loginOrCreateOAuth(int $providerId, string $providerUserId, string $email): array
    {
        // Chercher si le provider est déjà lié
        $userAuth = UserAuth::where('provider_id', $providerId)
                            ->where('provider_user_id', $providerUserId)
                            ->with('user')
                            ->first();

        if ($userAuth) {
            $user = $userAuth->user;
        } else {
            // Chercher si l'email existe déjà
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'id'       => Str::uuid(),
                    'email'    => $email,
                    'role_id'  => 3,
                ]);
            }

            UserAuth::create([
                'id'               => Str::uuid(),
                'user_id'          => $user->id,
                'provider_id'      => $providerId,
                'provider_user_id' => $providerUserId,
            ]);
        }

        $token = $user->createToken('oauth-token')->plainTextToken;

        return ['user' => $user->load('role'), 'token' => $token];
    }

    /**
     * Déconnexion (révocation du token courant)
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}