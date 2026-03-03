<?php

namespace Tests\Feature\Api;

use App\Models\Prize;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrizeApiTest extends TestCase
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

    // ─── GET /api/prizes ──────────────────────────────────────────────────────

    public function test_anyone_can_list_prizes(): void
    {
        Prize::factory()->count(3)->create();

        $response = $this->getJson('/api/prizes');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data'])
                 ->assertJson(['success' => true]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_prizes_list_is_empty_when_no_prizes(): void
    {
        $response = $this->getJson('/api/prizes');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    // ─── POST /api/admin/prizes ───────────────────────────────────────────────

    public function test_admin_can_create_prize(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/prizes', [
                             'name'        => 'Thé Bio Premium',
                             'description' => 'Un thé bio de qualité supérieure',
                             'stock'       => 100,
                         ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure(['data' => ['id', 'name', 'stock']]);

        $this->assertDatabaseHas('prizes', ['name' => 'Thé Bio Premium', 'stock' => 100]);
    }

    public function test_regular_user_cannot_create_prize(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/admin/prizes', [
                             'name'  => 'Lot interdit',
                             'stock' => 10,
                         ]);

        $response->assertStatus(403);
    }

    public function test_prize_creation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/prizes', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'stock']);
    }

    public function test_prize_stock_cannot_be_negative(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/prizes', [
                             'name'  => 'Lot test',
                             'stock' => -5,
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['stock']);
    }

    // ─── PUT /api/admin/prizes/{id} ───────────────────────────────────────────

    public function test_admin_can_update_prize(): void
    {
        $prize = Prize::factory()->create(['stock' => 10]);

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->putJson("/api/admin/prizes/{$prize->id}", [
                             'name'  => 'Nouveau nom',
                             'stock' => 50,
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('prizes', ['id' => $prize->id, 'stock' => 50]);
    }

    public function test_update_prize_returns_404_for_unknown_id(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->putJson('/api/admin/prizes/fake-uuid', ['stock' => 10]);

        $response->assertStatus(404);
    }

    // ─── DELETE /api/admin/prizes/{id} ───────────────────────────────────────

    public function test_admin_can_delete_prize(): void
    {
        $prize = Prize::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->deleteJson("/api/admin/prizes/{$prize->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('prizes', ['id' => $prize->id]);
    }

    public function test_regular_user_cannot_delete_prize(): void
    {
        $prize = Prize::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/admin/prizes/{$prize->id}");

        $response->assertStatus(403);
    }
}