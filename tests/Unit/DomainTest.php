<?php

namespace Tests\Unit;

use App\Exceptions\TicketCodeException;
use App\Models\Prize;
use App\Models\TicketCode;
use App\Services\ParticipationService;
use Tests\TestCase;

class ParticipationServiceTest extends TestCase
{
    private ParticipationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ParticipationService();
    }

    public function test_throws_exception_for_nonexistent_code(): void
    {
        [$user] = $this->createUserWithRole('user');

        $this->expectException(TicketCodeException::class);

        $this->service->participate($user, 'CODE_INEXISTANT');
    }

    public function test_throws_exception_for_already_used_code(): void
    {
        [$user] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->used()->create();

        $this->expectException(TicketCodeException::class);

        $this->service->participate($user, $ticket->code);
    }

    public function test_participation_decrements_prize_stock(): void
    {
        [$user] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->create();
        $prize  = Prize::factory()->create(['stock' => 5]);

        $this->service->participate($user, $ticket->code);

        $this->assertDatabaseHas('prizes', [
            'id'    => $prize->id,
            'stock' => 4,
        ]);
    }

    public function test_get_stats_returns_correct_structure(): void
    {
        $stats = $this->service->getStats();

        $this->assertArrayHasKey('total_participations', $stats);
        $this->assertArrayHasKey('total_winners', $stats);
        $this->assertArrayHasKey('prizes_remaining_stock', $stats);
        $this->assertArrayHasKey('total_redemptions', $stats);
    }
}

// ─── PrizeModelTest ────────────────────────────────────────────────────────────

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
            'id'    => $prize->id,
            'stock' => 9,
        ]);
    }
}