<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_enable', function (Builder $builder) {
            $builder->where('is_enable', 1)
                ->where(function ($query) {
//                    if(!auth()->user()->can('PCODE_011')) {
//                        $query->where('is_public', 1);
//                    }
                });
        });
    }

    protected $casts = [
        'login_openvpn' => 'boolean',
        'login_ssh' => 'boolean',
        'login_softether' => 'boolean',
        'login_ss' => 'boolean',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'is_enable' => 'boolean',
    ];
}
