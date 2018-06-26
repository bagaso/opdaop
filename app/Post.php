<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
        'is_pinned' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\User')->withDefault([
            'username' => '###'
        ]);
    }

    public function scopePostList($query)
    {
        if(!Auth::check()) {
            return $query->where('is_public', 1);
        }
    }
}
