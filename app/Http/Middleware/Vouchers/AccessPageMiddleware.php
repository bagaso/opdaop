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
            if($page == 'generate') {
                if(auth()->user()->can('MANAGE_VOUCHER')) {
                    return $next($request);
                }
            }
            if($page == 'apply') {
                if(auth()->user()->can('APPLY_VOUCHER_TO_ACCOUNT')) {
                    return $next($request);
                }
            }
        }
        return redirect(route('account.profile'));
    }
}
