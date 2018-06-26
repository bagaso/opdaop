<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AdminCreditLog extends Model
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

    public function user_from()
    {
        return $this->belongsTo('App\User', 'user_id_from')->withDefault([
            'username' => '###',
        ]);
    }

    public function user_to()
    {
        return $this->belongsTo('App\User', 'user_id_to')->withDefault([
            'username' => '###',
        ]);
    }

//    public function getUserFromAttribute() {
//        if(!is_null($this->userfrom1)) {
//            return $this->userfrom1->username;
//        }
//        return '###';
//    }
//
//    public function getUserToAttribute() {
//        if($this->user_id_to == 0) {
//            return '---';
//        }
//        if(!is_null($this->userto1)) {
//            return $this->userto1->username;
//        }
//        return '###';
//    }

    protected $hidden = [
        'user_id_from', 'user_id_to', //'userfrom1', 'userto1',
    ];

}
