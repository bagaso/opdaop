<?php

namespace App\Http\Middleware\AuthorizedResellers;

use Closure;
use Illuminate\Support\Facades\Auth;

class AccessPageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            if(!app('settings')->enable_authorized_reseller && !auth()->user()->isAdmin()) {
                return redirect(route('account.profile'));
            }
        } else {
            if(!app('settings')->enable_authorized_reseller) {
                return redirect(route('index'));
            }
        }
        return $next($request);
    }
}
