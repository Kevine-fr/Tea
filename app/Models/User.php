<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUuids, HasApiTokens;

    protected $fillable = [
        'email',
        'password_hash',
        'birth_date',
        'role_id',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'birth_date'  => 'date',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    // Alias pour compatibilité Auth (Laravel attend "password")
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // Relations
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function userAuths(): HasMany
    {
        return $this->hasMany(UserAuth::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role_id === Role::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->role_id === Role::EMPLOYEE;
    }
}