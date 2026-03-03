<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class RedemptionResource extends JsonResource {
    public function toArray($request): array {
        return ["id" => $this->id, "participation_id" => $this->participation_id, "method" => $this->method, "status" => $this->status];
    }
}