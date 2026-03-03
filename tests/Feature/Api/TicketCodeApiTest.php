<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCodeApiTest extends TestCase
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

    // ─── POST /api/admin/tickets/generate ─────────────────────────────────────

    public function test_admin_can_generate_tickets(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', [
                             'quantity' => 10,
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure(['data' => ['created']]);

        $this->assertEquals(10, TicketCode::count());
    }

    public function test_generate_fails_without_quantity(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['quantity']);
    }

    public function test_generate_fails_with_zero_quantity(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', ['quantity' => 0]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['quantity']);
    }

    public function test_generate_fails_with_excessive_quantity(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', ['quantity' => 99999]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['quantity']);
    }

    public function test_regular_user_cannot_generate_tickets(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', ['quantity' => 5]);

        $response->assertStatus(403);
    }

    public function test_employee_cannot_generate_tickets(): void
    {
        $response = $this->actingAs($this->employee, 'sanctum')
                         ->postJson('/api/admin/tickets/generate', ['quantity' => 5]);

        $response->assertStatus(403);
    }

    // ─── GET /api/admin/tickets/stats ─────────────────────────────────────────

    public function test_admin_can_see_ticket_stats(): void
    {
        TicketCode::factory()->count(5)->create();
        TicketCode::factory()->used()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/admin/tickets/stats');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'total'     => 8,
                         'used'      => 3,
                         'available' => 5,
                     ],
                 ]);
    }

    public function test_employee_can_see_ticket_stats(): void
    {
        $response = $this->actingAs($this->employee, 'sanctum')
                         ->getJson('/api/admin/tickets/stats');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['total', 'used', 'available']]);
    }

    public function test_regular_user_cannot_see_ticket_stats(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/admin/tickets/stats');

        $response->assertStatus(403);
    }
}