<?php

namespace Tests\Feature\Api;

use App\Models\Prize;
use App\Models\Role;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipationApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insertOrIgnore([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'employee'],
            ['id' => 3, 'name' => 'user'],
        ]);

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->admin()->create();
    }

    // ─── POST /api/participations ─────────────────────────────────────────────

    public function test_user_can_participate_with_valid_code(): void
    {
        $ticket = TicketCode::factory()->create();
        Prize::factory()->create(['stock' => 10]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/participations', ['code' => $ticket->code]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('participations', ['user_id' => $this->user->id]);
        $this->assertDatabaseHas('ticket_codes', ['code' => $ticket->code, 'is_used' => true]);
    }

    public function test_participation_assigns_a_prize_when_stock_available(): void
    {
        $ticket = TicketCode::factory()->create();
        $prize  = Prize::factory()->create(['stock' => 5]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/participations', ['code' => $ticket->code]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('participations', [
            'user_id'  => $this->user->id,
            'prize_id' => $prize->id,
        ]);
    }

    public function test_participation_has_no_prize_when_stock_is_zero(): void
    {
        $ticket = TicketCode::factory()->create();
        Prize::factory()->outOfStock()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/participations', ['code' => $ticket->code]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('participations', [
            'user_id'  => $this->user->id,
            'prize_id' => null,
        ]);
    }

    public function test_cannot_participate_with_already_used_code(): void
    {
        $ticket = TicketCode::factory()->used()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/participations', ['code' => $ticket->code]);

        $response->assertStatus(409);
    }

    public function test_cannot_participate_with_nonexistent_code(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/participations', ['code' => 'FAKECODE99']);

        $response->assertStatus(404);
    }

    public function test_guest_cannot_participate(): void
    {
        $ticket = TicketCode::factory()->create();

        $response = $this->postJson('/api/participations', ['code' => $ticket->code]);

        $response->assertStatus(401);
    }

    // ─── GET /api/participations ──────────────────────────────────────────────

    public function test_user_can_see_their_participations(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/participations');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'meta']);
    }

    public function test_user_cannot_see_other_users_participations(): void
    {
        $otherUser = User::factory()->create();
        $ticket    = TicketCode::factory()->create(['is_used' => true]);

        \App\Models\Participation::create([
            'id'                 => \Illuminate\Support\Str::uuid(),
            'user_id'            => $otherUser->id,
            'ticket_code_id'     => $ticket->id,
            'participation_date' => now(),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/participations');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    // ─── GET /api/admin/participations ────────────────────────────────────────

    public function test_admin_can_see_all_participations(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/participations');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'meta']);
    }

    public function test_regular_user_cannot_access_admin_route(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/admin/participations');

        $response->assertStatus(403);
    }

    // ─── GET /api/admin/stats ─────────────────────────────────────────────────

    public function test_admin_can_see_stats(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['total_participations', 'total_winners', 'prizes_remaining_stock'],
                 ]);
    }
}