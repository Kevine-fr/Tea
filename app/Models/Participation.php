<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participation extends Model
{
    use HasUuids;

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'ticket_code_id',
        'prize_id',
        'participation_date',
    ];

    protected function casts(): array
    {
        return [
            'participation_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketCode(): BelongsTo
    {
        return $this->belongsTo(TicketCode::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }

    public function redemption(): HasOne
    {
        return $this->hasOne(Redemption::class);
    }

    public function hasWon(): bool
    {
        return $this->prize_id !== null;
    }

    public function isRedeemed(): bool
    {
        return $this->redemption !== null;
    }
}