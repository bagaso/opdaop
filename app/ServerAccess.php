<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerAccess extends Model
{
    protected $casts = [
        'is_paid' => 'boolean',
        'is_private' => 'boolean',
        'multi_login' => 'boolean',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'is_enable' => 'boolean',
    ];
}
