<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Redemption extends Model
{
    use HasUuids;

    public $timestamps = false;
    protected $fillable = [
        'participation_id',
        'method',
        'status',
        'requested_at',
        'completed_at',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_COMPLETED = 'completed';

    const METHOD_STORE  = 'store';
    const METHOD_MAIL   = 'mail';
    const METHOD_ONLINE = 'online';

    protected function casts(): array
    {
        return [
            'requested_at'  => 'datetime',
            'completed_at'  => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function complete(): void
    {
        $this->update([
            'status'       => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }
}