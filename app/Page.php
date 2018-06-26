<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function scopeCustomPage($query)
    {
        return $query->where('id', '<>', 1);
    }
}
