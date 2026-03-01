<?php

namespace Tests\Feature;

use App\Models\Participation;
use App\Models\Prize;
use App\Models\Redemption;
use App\Models\TicketCode;
use Tests\TestCase;
use Illuminate\Support\Str;

class RedemptionTest extends TestCase
{
    private function createWinningParticipation($user): Participation
    {
        $prize  = Prize::factory()->create(['stock' => 1]);
        $ticket = TicketCode::factory()->create();

        return Participation::create([
            'id'                 => Str::uuid(),
            'user_id'            => $user->id,
            'ticket_code_id'     => $ticket->id,
            'prize_id'           => $prize->id,
            'participation_date' => now(),
        ]);
    }

    private function createLosingParticipation($user): Participation
    {
        $ticket = TicketCode::factory()->create();

        return Participation::create([
            'id'                 => Str::uuid(),
            'user_id'            => $user->id,
            'ticket_code_id'     => $ticket->id,
            'prize_id'           => null,
            'participation_date' => now(),
        ]);
    }

    // ─── DEMANDE DE RÉCLAMATION ────────────────────────────────────────────────

    public function test_winner_can_request_redemption(): void
    {
        [$user, $token]   = $this->createUserWithRole('user');
        $participation = $this->createWinningParticipation($user);

        $response = $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'store',
        ], $this->authHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonPath('data.status', 'pending')
                 ->assertJsonPath('data.method', 'store');
    }

    public function test_loser_cannot_request_redemption(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $participation  = $this->createLosingParticipation($user);

        $response = $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'store',
        ], $this->authHeaders($token));

        $response->assertStatus(400)
                 ->assertJsonPath('success', false);
    }

    public function test_cannot_redeem_twice(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $participation  = $this->createWinningParticipation($user);

        // Première demande
        $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'store',
        ], $this->authHeaders($token));

        // Deuxième demande → doit échouer
        $response = $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'mail',
        ], $this->authHeaders($token));

        $response->assertStatus(409);
    }

    public function test_redemption_requires_valid_method(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $participation  = $this->createWinningParticipation($user);

        $response = $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'teleportation', // méthode invalide
        ], $this->authHeaders($token));

        $response->assertStatus(422);
    }

    // ─── ADMIN / EMPLOYEE ──────────────────────────────────────────────────────

    public function test_employee_can_see_pending_redemptions(): void
    {
        [$user, $userToken]         = $this->createUserWithRole('user');
        [$employee, $employeeToken] = $this->createUserWithRole('employee');

        $participation = $this->createWinningParticipation($user);
        $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'store',
        ], $this->authHeaders($userToken));

        $response = $this->getJson('/api/admin/redemptions', $this->authHeaders($employeeToken));

        $response->assertStatus(200);
    }

    public function test_employee_can_approve_redemption(): void
    {
        [$user, $userToken]         = $this->createUserWithRole('user');
        [$employee, $employeeToken] = $this->createUserWithRole('employee');

        $participation = $this->createWinningParticipation($user);
        $redemptionRes = $this->postJson('/api/redemptions', [
            'participation_id' => $participation->id,
            'method'           => 'store',
        ], $this->authHeaders($userToken));

        $redemptionId = $redemptionRes->json('data.id');

        $response = $this->patchJson(
            "/api/admin/redemptions/{$redemptionId}/status",
            ['status' => 'approved'],
            $this->authHeaders($employeeToken)
        );

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'approved');
    }

    public function test_regular_user_cannot_update_redemption_status(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        $redemption = Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $this->createWinningParticipation($user)->id,
            'method'           => 'store',
            'status'           => 'pending',
        ]);

        $response = $this->patchJson(
            "/api/admin/redemptions/{$redemption->id}/status",
            ['status' => 'completed'],
            $this->authHeaders($token)
        );

        $response->assertStatus(403);
    }
}