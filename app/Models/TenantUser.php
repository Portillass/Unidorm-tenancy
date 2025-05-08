<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TenantUser extends Authenticatable
{
    protected $table = 'users'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
