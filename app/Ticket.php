<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $casts = [
        'is_open' => 'boolean',
        'is_lock' => 'boolean',
    ];

    public function replies()
    {
        return $this->hasMany('App\ReplyTicket')->orderByDesc('created_at');
    }

    public function user_by()
    {
        return $this->belongsTo('App\User')->withDefault([
            'username' => '###',
        ]);
    }

    public function ticketOwner() {
        return $this->hasOne('App\ReplyTicket')->oldest();
    }

    public function latestReply() {
        return $this->hasOne('App\ReplyTicket')->latest();
    }

    public function getStatusAttribute()
    {
        if($this->is_lock) {
            return 'Locked';
        }
        if(!$this->is_open) {
            return 'Closed';
        }
        return 'Open';
    }

    public function getStatusClassAttribute()
    {
        if($this->is_lock) {
            return 'danger';
        }
        if(!$this->is_open) {
            return 'default';
        }
        return 'primary';
    }

    public function scopeAllTickets($query)
    {
        return $query->where(function ($query) {
            if(auth()->user()->cannot('MANAGE_SUPPORT')) {
                $query->where('user_id', auth()->user()->id);
            }
        })->where(function ($query) {
            if(auth()->user()->group_id > $this->user_by->group_id) {
                return true;
            }
            return false;
        });
    }

    public function scopeOpenTickets($query)
    {
        return $query->where(function ($query) {
            if(auth()->user()->can('MANAGE_SUPPORT')) {
                $query->where('is_open', 1)
                      ->where('is_lock', 0);
            } else {
                $query->where('user_id', auth()->user()->id)
                      ->where('is_open', 1)
                      ->where('is_lock', 0);
            }
        });
    }

    public function scopeCloseTickets($query)
    {
        return $query->where(function ($query) {
            if(auth()->user()->can('MANAGE_SUPPORT')) {
                $query->where('is_open', 0)
                    ->where('is_lock', 0);
            } else {
                $query->where('user_id', auth()->user()->id)
                    ->where('is_open', 0)
                    ->where('is_lock', 0);
            }
        });
    }

    public function scopeLockTickets($query)
    {
        return $query->where(function ($query) {
            if(auth()->user()->can('MANAGE_SUPPORT')) {
                $query->where('is_lock', 1);
            } else {
                $query->where('user_id', auth()->user()->id)
                    ->where('is_lock', 1);
            }
        });
    }
}
