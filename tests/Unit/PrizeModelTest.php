<?php

namespace Tests\Unit;

use App\Models\Prize;
use Tests\TestCase;

class PrizeModelTest extends TestCase
{
    public function test_is_available_returns_true_when_stock_positive(): void
    {
        $prize = Prize::factory()->create(['stock' => 3]);
        $this->assertTrue($prize->isAvailable());
    }

    public function test_is_available_returns_false_when_stock_zero(): void
    {
        $prize = Prize::factory()->outOfStock()->create();
        $this->assertFalse($prize->isAvailable());
    }

    public function test_decrement_stock_reduces_by_one(): void
    {
        $prize = Prize::factory()->create(['stock' => 10]);
        $prize->decrementStock();

        $this->assertDatabaseHas('prizes', [
            'id' => $prize->id,
            'stock' => 9,
        ]);
    }
}