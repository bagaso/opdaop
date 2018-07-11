<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UserActionLog extends Model
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
        'user_id', 'user_id_related', 'action', 'from_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'user_id_related',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault([
            'username' => '###',
        ]);
    }

    public function user_related()
    {
        return $this->belongsTo('App\User', 'user_id_related')->withDefault([
            'username' => '###',
        ]);
    }
}
