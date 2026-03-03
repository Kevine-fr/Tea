<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class ParticipationResource extends JsonResource {
    public function toArray($request): array {
        return ["id" => $this->id, "user_id" => $this->user_id, "prize_id" => $this->prize_id];
    }
}