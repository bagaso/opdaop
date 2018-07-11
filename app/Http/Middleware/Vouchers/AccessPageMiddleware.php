<?php

namespace App\Http\Middleware\Vouchers;

use Closure;

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
        if(auth()->user()->isActive()) {
            if(auth()->user()->isAdmin()) {
                return $next($request);
            }
            if(in_array(auth()->user()->group->id, [2,3,4])) {
                return $next($request);
            }
        }
        return redirect(route('account.profile'));
    }
}
