<?php

namespace Tests\Feature\Api;

use App\Models\Participation;
use App\Models\Prize;
use App\Models\Redemption;
use App\Models\Role;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RedemptionApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insertOrIgnore([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'employee'],
            ['id' => 3, 'name' => 'user'],
        ]);

        $this->user     = User::factory()->create();
        $this->admin    = User::factory()->admin()->create();
        $this->employee = User::factory()->employee()->create();
    }

    /**
     * Créer une participation gagnante pour un utilisateur
     */
    private function createWinningParticipation(User $user): Participation
    {
        $ticket = TicketCode::factory()->used()->create();
        $prize  = Prize::factory()->create();

        return Participation::create([
            'id'                 => Str::uuid(),
            'user_id'            => $user->id,
            'ticket_code_id'     => $ticket->id,
            'prize_id'           => $prize->id,
            'participation_date' => now(),
        ]);
    }

    /**
     * Créer une participation perdante pour un utilisateur
     */
    private function createLosingParticipation(User $user): Participation
    {
        $ticket = TicketCode::factory()->used()->create();

        return Participation::create([
            'id'                 => Str::uuid(),
            'user_id'            => $user->id,
            'ticket_code_id'     => $ticket->id,
            'prize_id'           => null,
            'participation_date' => now(),
        ]);
    }

    // ─── POST /api/redemptions ────────────────────────────────────────────────

    public function test_winner_can_request_redemption(): void
    {
        $participation = $this->createWinningParticipation($this->user);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/redemptions', [
                             'participation_id' => $participation->id,
                             'method'           => 'store',
                         ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure(['data' => ['id', 'method', 'status']]);

        $this->assertDatabaseHas('redemptions', [
            'participation_id' => $participation->id,
            'status'           => 'pending',
        ]);
    }

    public function test_loser_cannot_request_redemption(): void
    {
        $participation = $this->createLosingParticipation($this->user);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/redemptions', [
                             'participation_id' => $participation->id,
                             'method'           => 'store',
                         ]);

        $response->assertStatus(400);
    }

    public function test_cannot_redeem_twice(): void
    {
        $participation = $this->createWinningParticipation($this->user);

        Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $participation->id,
            'method'           => 'store',
            'status'           => 'pending',
            'requested_at'     => now(),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/redemptions', [
                             'participation_id' => $participation->id,
                             'method'           => 'store',
                         ]);

        $response->assertStatus(409);
    }

    public function test_redemption_requires_valid_method(): void
    {
        $participation = $this->createWinningParticipation($this->user);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/redemptions', [
                             'participation_id' => $participation->id,
                             'method'           => 'invalid_method',
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['method']);
    }

    // ─── GET /api/admin/redemptions ───────────────────────────────────────────

    public function test_employee_can_see_pending_redemptions(): void
    {
        $response = $this->actingAs($this->employee, 'sanctum')
                         ->getJson('/api/admin/redemptions');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    public function test_regular_user_cannot_see_redemptions_list(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/admin/redemptions');

        $response->assertStatus(403);
    }

    // ─── PATCH /api/admin/redemptions/{id}/status ─────────────────────────────

    public function test_employee_can_approve_redemption(): void
    {
        $participation = $this->createWinningParticipation($this->user);

        $redemption = Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $participation->id,
            'method'           => 'store',
            'status'           => 'pending',
            'requested_at'     => now(),
        ]);

        $response = $this->actingAs($this->employee, 'sanctum')
                         ->patchJson("/api/admin/redemptions/{$redemption->id}/status", [
                             'status' => 'approved',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('redemptions', [
            'id'     => $redemption->id,
            'status' => 'approved',
        ]);
    }

    public function test_regular_user_cannot_update_redemption_status(): void
    {
        $participation = $this->createWinningParticipation($this->user);

        $redemption = Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $participation->id,
            'method'           => 'store',
            'status'           => 'pending',
            'requested_at'     => now(),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->patchJson("/api/admin/redemptions/{$redemption->id}/status", [
                             'status' => 'approved',
                         ]);

        $response->assertStatus(403);
    }
}