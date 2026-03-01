<?php

namespace Database\Factories;

use App\Models\TicketCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketCodeFactory extends Factory
{
    protected $model = TicketCode::class;

    public function definition(): array
    {
        return [
            'id'      => Str::uuid(),
            'code'    => strtoupper(Str::random(8)),
            'is_used' => false,
        ];
    }

    public function used(): static
    {
        return $this->state(['is_used' => true]);
    }
}

// ─── PrizeFactory ──────────────────────────────────────────────────────────────

namespace Database\Factories;

use App\Models\Prize;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PrizeFactory extends Factory
{
    protected $model = Prize::class;

    public function definition(): array
    {
        return [
            'id'          => Str::uuid(),
            'name'        => fake()->words(3, true),
            'description' => fake()->sentence(),
            'stock'       => fake()->numberBetween(1, 50),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }
}