<?php

namespace Tests\Feature;

use App\Models\Prize;
use App\Models\TicketCode;
use Tests\TestCase;

class ParticipationTest extends TestCase
{
    // ─── PARTICIPER ────────────────────────────────────────────────────────────

    public function test_user_can_participate_with_valid_code(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->create();

        $response = $this->postJson('/api/participations', [
            'code' => $ticket->code,
        ], $this->authHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => ['id', 'has_won', 'participation_date', 'ticket_code'],
                 ]);

        $this->assertDatabaseHas('participations', [
            'user_id'        => $user->id,
            'ticket_code_id' => $ticket->id,
        ]);

        // Le ticket doit être marqué comme utilisé
        $this->assertDatabaseHas('ticket_codes', [
            'id'      => $ticket->id,
            'is_used' => true,
        ]);
    }

    public function test_participation_assigns_a_prize_when_stock_available(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->create();
        Prize::factory()->create(['stock' => 10]);

        $response = $this->postJson('/api/participations', [
            'code' => $ticket->code,
        ], $this->authHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonPath('data.has_won', true);

        $this->assertDatabaseHas('participations', [
            'ticket_code_id' => $ticket->id,
        ]);
    }

    public function test_participation_has_no_prize_when_stock_is_zero(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->create();
        // Aucun lot disponible

        $response = $this->postJson('/api/participations', [
            'code' => $ticket->code,
        ], $this->authHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonPath('data.has_won', false);
    }

    public function test_cannot_participate_with_already_used_code(): void
    {
        [$user, $token] = $this->createUserWithRole('user');
        $ticket = TicketCode::factory()->used()->create();

        $response = $this->postJson('/api/participations', [
            'code' => $ticket->code,
        ], $this->authHeaders($token));

        $response->assertStatus(409)
                 ->assertJsonPath('success', false);
    }

    public function test_cannot_participate_with_nonexistent_code(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        $response = $this->postJson('/api/participations', [
            'code' => 'INVALID999',
        ], $this->authHeaders($token));

        $response->assertStatus(404);
    }

    public function test_guest_cannot_participate(): void
    {
        $ticket = TicketCode::factory()->create();

        $response = $this->postJson('/api/participations', [
            'code' => $ticket->code,
        ]);

        $response->assertStatus(401);
    }

    // ─── HISTORIQUE ────────────────────────────────────────────────────────────

    public function test_user_can_see_their_participations(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        // Créer 2 participations pour cet user
        $tickets = TicketCode::factory()->count(2)->create();
        foreach ($tickets as $ticket) {
            $this->postJson('/api/participations', [
                'code' => $ticket->code,
            ], $this->authHeaders($token));
        }

        $response = $this->getJson('/api/participations', $this->authHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonPath('meta.total', 2);
    }

    public function test_user_cannot_see_other_users_participations(): void
    {
        [$user1, $token1] = $this->createUserWithRole('user');
        [$user2, $token2] = $this->createUserWithRole('user');

        $ticket = TicketCode::factory()->create();
        $this->postJson('/api/participations', ['code' => $ticket->code], $this->authHeaders($token1));

        // user2 voit son historique (vide)
        $response = $this->getJson('/api/participations', $this->authHeaders($token2));
        $response->assertStatus(200)
                 ->assertJsonPath('meta.total', 0);
    }

    // ─── ADMIN ─────────────────────────────────────────────────────────────────

    public function test_admin_can_see_all_participations(): void
    {
        [$admin, $adminToken] = $this->createUserWithRole('admin');
        [$user, $userToken]   = $this->createUserWithRole('user');

        $ticket = TicketCode::factory()->create();
        $this->postJson('/api/participations', ['code' => $ticket->code], $this->authHeaders($userToken));

        $response = $this->getJson('/api/admin/participations', $this->authHeaders($adminToken));

        $response->assertStatus(200)
                 ->assertJsonPath('meta.total', 1);
    }

    public function test_regular_user_cannot_access_admin_route(): void
    {
        [$user, $token] = $this->createUserWithRole('user');

        $response = $this->getJson('/api/admin/participations', $this->authHeaders($token));

        $response->assertStatus(403);
    }

    public function test_admin_can_see_stats(): void
    {
        [$admin, $token] = $this->createUserWithRole('admin');

        $response = $this->getJson('/api/admin/stats', $this->authHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'total_participations',
                         'total_winners',
                         'prizes_remaining_stock',
                         'total_redemptions',
                     ],
                 ]);
    }
}