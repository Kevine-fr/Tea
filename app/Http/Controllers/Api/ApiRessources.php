<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// ─── UserResource ──────────────────────────────────────────────────────────────
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'email'      => $this->email,
            'birth_date' => $this->birth_date?->toDateString(),
            'role'       => $this->whenLoaded('role', fn () => [
                'id'   => $this->role->id,
                'name' => $this->role->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

// ─── PrizeResource ─────────────────────────────────────────────────────────────
class PrizeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'stock'       => $this->stock,
        ];
    }
}

// ─── TicketCodeResource ────────────────────────────────────────────────────────
class TicketCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'is_used'    => $this->is_used,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

// ─── ParticipationResource ─────────────────────────────────────────────────────
class ParticipationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'participation_date' => $this->participation_date?->toISOString(),
            'has_won'            => $this->hasWon(),
            'ticket_code'        => $this->whenLoaded('ticketCode', fn () => [
                'code' => $this->ticketCode->code,
            ]),
            'prize'              => $this->whenLoaded('prize', fn () =>
                $this->prize ? new PrizeResource($this->prize) : null
            ),
            'redemption'         => $this->whenLoaded('redemption', fn () =>
                $this->redemption ? new RedemptionResource($this->redemption) : null
            ),
            'user'               => $this->whenLoaded('user', fn () =>
                new UserResource($this->user)
            ),
        ];
    }
}

// ─── RedemptionResource ────────────────────────────────────────────────────────
class RedemptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'method'       => $this->method,
            'status'       => $this->status,
            'requested_at' => $this->requested_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
        ];
    }
}