<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketCode extends Model
{
    use HasUuids, HasFactory;

    public $timestamps = false;
    protected $fillable = ['code', 'is_used'];

    protected function casts(): array
    {
        return [
            'is_used'    => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function participation(): HasOne
    {
        return $this->hasOne(Participation::class);
    }

    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }
}