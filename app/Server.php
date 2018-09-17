<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'server_access_id',
    ];

    protected $casts = [
        'limit_bandwidth' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function server_access()
    {
        return $this->belongsTo('App\ServerAccess');
    }

    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription')->orderBy('subscription_id');
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\User', 'App\OnlineUser',
            'server_id', 'user_id', 'id'
        );
    }

    public function privateUsers()
    {
        return $this->belongsToMany('App\User');
    }

    public function online_users()
    {
        return $this->hasMany('App\OnlineUser');
    }

    public function scopeServerOpenvpn($query)
    {
        return $query->where([['server_type', 'openvpn']]);
    }

    public function scopeFreeServerOpenvpn($query)
    {
        return $query->where([['server_access_id', 1]]);
    }

    public function scopePaidServerOpenvpn($query)
    {
        return $query->whereIn('server_access_id', [2,3,4]);
    }

    public function scopePremiumServerOpenvpn($query)
    {
        return $query->where([['server_access_id', 2]]);
    }

    public function scopeVIPServerOpenvpn($query)
    {
        return $query->where([['server_access_id', 3]]);
    }

    public function scopePrivateServerOpenvpn($query)
    {
        return $query->where([['server_access_id', 4]]);
    }

    public function scopeNormalServerOpenvpn($query)
    {
        return $query->where([['server_access_id', 2]]);
    }

    public function scopeSpecialServerOpenvpn($query)
    {
        return $query->whereIn('server_access_id', [3, 4]);
    }

    public function isSpecialServer()
    {
        return in_array($this->server_access_id, [3,4]);
    }
}
