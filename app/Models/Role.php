<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    // Constants pour éviter les magic strings
    const ADMIN    = 1;
    const EMPLOYEE = 2;
    const USER     = 3;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}