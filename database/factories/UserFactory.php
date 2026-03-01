<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id'            => Str::uuid(),
            'email'         => fake()->unique()->safeEmail(),
            'password_hash' => Hash::make('password'),
            'birth_date'    => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'role_id'       => 3,
        ];
    }

    public function admin(): static
    {
        return $this->state(['role_id' => 1]);
    }

    public function employee(): static
    {
        return $this->state(['role_id' => 2]);
    }
}