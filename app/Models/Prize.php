<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prize extends Model
{
    use HasUuids, HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'description', 'stock'];

    protected function casts(): array
    {
        return [
            'stock'      => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function decrementStock(): void
    {
        $this->decrement('stock');
    }
}