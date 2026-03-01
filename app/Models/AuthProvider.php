<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthProvider extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    const LOCAL    = 1;
    const GOOGLE   = 2;
    const FACEBOOK = 3;

    public function userAuths(): HasMany
    {
        return $this->hasMany(UserAuth::class, 'provider_id');
    }
}