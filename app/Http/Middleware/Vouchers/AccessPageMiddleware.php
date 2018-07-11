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
        if(!auth()->user()->isAdmin())
        {
            if(auth()->user()->cannot('MANAGE_VOUCHER')) {
                return $next($request);
            }
        }
        return redirect(route('account.profile'));
    }
}
