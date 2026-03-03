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