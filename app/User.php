<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email', 'fullname', 'group_id', 'subscription_id', 'status_id', 'service_password', 'password_openvpn', 'password_ssh', 'value', 'parent_id', 'expired_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'group_id', 'subscription_id', 'status_id', 'parent_id', 'freeze_mode', 'service_password', 'password_openvpn', 'password_ssh', 'value', 'permissions', 'previousMonthRenew', 'currentMonthRenew',
    ];

    protected $dates = [
        'expired_at', 'deleted_at', 'freeze_start', 'login_datetime',
    ];

    protected $casts = [
        'freeze_mode' => 'boolean',
        'distributor' => 'boolean',
        'f_login_openvpn' => 'boolean',
        'f_login_ssh' => 'boolean',
        'f_login_softether' => 'boolean',
        'f_login_ss' => 'boolean',
    ];

    public function isActive() {
        return ($this->isAdmin() || $this->status_id == 2);
    }

    public function isAdmin()
    {
        return $this->group_id === 1;
    }

    public function group()
    {
        return $this->belongsTo('App\Group')->withoutGlobalScopes();
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function subscription()
    {
        return $this->belongsTo('App\Subscription')->withoutGlobalScopes();
    }

    public function isDownline()
    {
        if($this->isAdmin()) return false;
        if(auth()->user()->id == $this->parent_id) return true;
        return false;
    }

    public function getCreditsAttribute($value)
    {
        return $this->can('UNLIMITED_CREDIT') ? 'No Limit' : $value;
    }

    public function getCreditsClassAttribute()
    {
        if($this->can('UNLIMITED_CREDIT')) {
            return 'success';
        }
        return $this->getOriginal('credits') > 0 ? 'primary' : 'danger';
    }

    public function getExpiredAtAttribute($value)
    {
        if($this->isAdmin()) {
            return 'No Limit';
        }
        if($this->freeze_mode) {
            return 'Freezed';
        }
        $current = Carbon::now();
        $dt = Carbon::parse($value ? $value : Carbon::now()->subDay());
        if($current->gte($dt)) {
            return 'Expired';
        }
        if ($dt->diffInDays(Carbon::now()) > 1)
            return $dt->diffInDays(Carbon::now()) . ' Days';
        else
            return $dt->diffForHumans(null, true);
    }

    public function getExpiredAtClassAttribute()
    {
        if($this->isAdmin()) {
            return 'success';
        }
        if($this->freeze_mode) {
            return 'info';
        }
        $current = Carbon::now();
        $dt = Carbon::parse($this->getOriginal('expired_at'));
        if($current->gte($dt)) {
            return 'default';
        }
        return 'primary';
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }

    public function upline()
    {
        return $this->belongsTo('App\User', 'parent_id')->withDefault([
            'username' => '###',
        ]);
    }

    public function scopeUserAll($query, $user)
    {
        return $query->where([['group_id', '>', $user->group_id],['group_id', '<>', 2]])->where(function ($query) use ($user) {
            if($user->cannot('manage-users-others')) {
                $query->where('parent_id', $user->id);
            }
        });
    }

    public function scopeSubAdmins($query)
    {
        return $query->where('group_id', 2);
    }

    public function scopeResellers($query, $user)
    {
        return $query->where('group_id', 3)->where(function ($query) use ($user) {
            if(!$user->isAdmin()) {
                $query->where('parent_id', $user->id);
            }
        });
    }

    public function scopeSubResellers($query, $user)
    {
        return $query->where('group_id', 4)->where(function ($query) use ($user) {
            if(!$user->isAdmin()) {
                $query->where('parent_id', $user->id);
            }
        });
    }

    public function scopeClients($query, $user)
    {
        return $query->where('group_id', 5)->where(function ($query) use ($user) {
            if(!$user->isAdmin()) {
                $query->where('parent_id', $user->id);
            }
        });
    }

    public function scopeUserOther($query, $user)
    {
        return $query->where([['group_id', '>', $user->group_id],['group_id', '<>', 2],['parent_id', '<>', $user->id]]);
    }

    public function scopeTrashes($query, $user)
    {
        return $query->onlyTrashed()->where([['group_id', '>', $user->group_id]])->where(function ($query) use ($user) {
            if($user->cannot('manage-users-others')) {
                $query->where('parent_id', $user->id);
            }
        });
    }

    public function scopeDownLines($query, $user)
    {
        return $query->where(function ($query) use ($user) {
            $query->where([['id', '<>', $user->id],['group_id', '>', $user->group_id],['parent_id', $user->id]]);
        });
    }

    public function scopeDistributors($query)
    {
        return $query->whereIn('group_id', [2,3,4])->where([['status_id', 2], ['distributor', 1], ['credits', '>', 0]]);
    }

    public function credit_logs() {
        return $this->hasMany('App\UserCreditLog');
    }

    public function previousMonthRenew() {
        return $this->hasMany('App\UserCreditLog')
            ->where('direction', '=', 'IN')
            ->whereIn('type', ['TRANSFER-01', 'TRANSFER-02'])
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->orderBy('created_at', 'desc');
    }

    public function currentMonthRenew() {
        return $this->hasMany('App\UserCreditLog')
            ->where('direction', '=', 'IN')
            ->whereIn('type', ['TRANSFER-01', 'TRANSFER-02'])
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->orderBy('created_at', 'desc');
    }

    public function latestRenew() {
        return $this->hasOne('App\UserCreditLog')
            ->where('direction', '=', 'IN')
            ->whereIn('type', ['TRANSFER-01', 'TRANSFER-02'])
            ->latest()->withDefault([
            'created_at' => null,
        ]);
    }

    public function vpn() {
        return $this->hasMany('App\OnlineUser');
    }

    public function getConsumableDataAttribute($value)
    {
        return $this->sizeformat($value);
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

    public function freeSubscription()
    {
        return $this->expired_at == 'Expired';
    }

    public function paidSubscription()
    {
        return $this->expired_at != 'Expired';
    }

    public function freezedSubscription()
    {
        return $this->freeze_mode == 1;
    }

    public function scopeTotalUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id);
    }

    public function scopeActiveUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('status_id', 2);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('status_id', 2);
    }

    public function scopeInActiveUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('status_id', 1);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('status_id', 1);
    }

    public function scopeSuspendedUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('status_id', 3);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('status_id', 3);
    }

    public function scopeActivePaidUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('expired_at', '>', Carbon::now());
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('expired_at', '>', Carbon::now());
    }

    public function scopeActiveFreezedUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('free_mode', 1);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('free_mode', 1);
    }

    public function scopeBronzeUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('subscription_id', 1);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('subscription_id', 1);
    }

    public function scopeSilverUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('subscription_id', 2);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('subscription_id', 2);
    }

    public function scopeGoldUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('subscription_id', 3);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('subscription_id', 3);
    }

    public function scopeDiamondUsers($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('subscription_id', 4);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('subscription_id', 4);
    }

    public function scopeNewUsersThisWeek($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('created_at', '>=', Carbon::now()->startOfWeek());
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('created_at', '>=', Carbon::now()->startOfWeek());
    }

    public function scopeNewUsersThisMonth($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->where('created_at', '>=', Carbon::now()->startOfMonth());
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->where('created_at', '>=', Carbon::now()->startOfMonth());
    }

    public function scopeNewUsersLastMonth($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
    }

    public function scopeUsersExpiresThisWeek($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->whereBetween('expired_at', [Carbon::now(), Carbon::now()->endOfWeek()]);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->whereBetween('expired_at', [Carbon::now(), Carbon::now()->endOfWeek()]);
    }

    public function scopeUsersExpiresThisMonth($query, $user = null)
    {
        if(auth()->user()->isAdmin() && $user == null) {
            return $query->where('group_id', '>', auth()->user()->group->id)->whereBetween('expired_at', [Carbon::now(), Carbon::now()->endOfMonth()]);
        }
        return $query->where('group_id', '>', $user == null ? auth()->user()->group->id : $user->group->id)->where('parent_id', $user == null ? auth()->user()->id : $user->id)->whereBetween('expired_at', [Carbon::now(), Carbon::now()->endOfMonth()]);
    }

    public function normalSubscription()
    {
        return $this->subscription_id === 1;
    }

    public function specialSubscription()
    {
        return in_array($this->subscription_id, [2,3,4]);
    }
}
