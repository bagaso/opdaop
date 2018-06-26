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
    public function handle($request, Closure $next, $page = '')
    {
        if(!auth()->user()->isAdmin())
        {
            if(auth()->user()->cannot('MANAGER_USER')) {
                if($page == 'generate') {
                    if(auth()->user()->cannot('MANAGE_VOUCHER')) {
                        return redirect(route('account.profile'));
                    }
                }
            }
//            if($page == 'apply') {
//                if(auth()->user()->cannot('APPLY_VOUCHER_TO_ACCOUNT')) {
//                    return redirect(route('account.profile'));
//                }
//            }
        }
        return $next($request);
    }
}
