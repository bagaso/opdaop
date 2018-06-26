<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdateJson extends Model
{
    protected $casts = [
        //'json_data' => 'array',
        'is_enable' => 'boolean',
    ];
}
