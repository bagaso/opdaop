<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Voucher extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $uuid = Uuid::uuid4();
            $model->id = $uuid->toString();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'duration',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'created_user_id',
    ];

    public function user_from()
    {
        return $this->belongsTo('App\User', 'created_user_id')->withDefault([
            'username' => '###'
        ]);
    }

    public function user_to()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault([
            'username' => '###'
        ]);
    }

    public function scopeFetchVoucherList($query, $user)
    {
        return $query->where(function ($query) use ($user) {
            if(!$user->isAdmin()) {
                $query->where('created_user_id', $user->id);
            }
        });
    }

    public function scopeFetchApplyVoucherList($query, $user)
    {
        return $query->where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function scopeFetchUsedVoucherList($query, $user)
    {
        return $query->where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }
}
