<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserIfSuspendedMiddleware
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
        if(Auth::check() && in_array(Auth::user()->status_id, [3])) {
            Auth::logout();
            return redirect(route('suspended'));
        }
        return $next($request);
    }
}
