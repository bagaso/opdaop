<?php

namespace App\Listeners;

use App\UserActionLog;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if($event->user->status_id !== 3) {
            $now = Carbon::now();
            $from_ip = Request::getClientIp();
            #Cookie::queue('lastlogin_datetime', $event->user->login_datetime, 525600 * 5);
            #Cookie::queue('lastlogin_ip', $event->user->login_ip, 525600 * 5);
            $event->user->timestamps = false;
            $event->user->last_login_datetime = $event->user->login_datetime;
            $event->user->last_login_ip = $event->user->login_ip;
            $event->user->login_datetime = $now;
            $event->user->login_ip = Request::getClientIp();
            $event->user->timestamps = false;
            $event->user->save();
            UserActionLog::create(['user_id' => $event->user->id, 'user_id_related' => $event->user->id, 'action' => 'Login Successful.', 'from_ip' => $from_ip]);
        }
    }
}
