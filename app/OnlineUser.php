<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class OnlineUser extends Model
{
    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'id';

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

    protected $fillable = [
        'user_id', 'server_id', 'user_ip', 'user_port', 'byte_sent', 'byte_received', 'data_available',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function server()
    {
        return $this->belongsTo('App\Server');
    }

    public function sizeformat($bytesize)
    {
        $i=0;
        while(abs($bytesize) >= 1024) {
            $bytesize=$bytesize/1024;
            $i++;
            if($i==4) break;
        }

        $units = array("Bytes","KB","MB","GB","TB");
        $newsize=round($bytesize,2);
        return("$newsize $units[$i]");
    }

    public function getByteSentAttribute($value) {
        return $this->sizeformat($value);
    }

    public function getByteReceivedAttribute($value) {
        return $this->sizeformat($value);
    }

    public function getCreatedAtAttribute($value) {
        $dt = Carbon::parse($value);
        if ($dt->diffInDays(Carbon::now()) > 1)
            return $dt->format('Y-M-d');
        else
            return $dt->diffForHumans();
    }

}
