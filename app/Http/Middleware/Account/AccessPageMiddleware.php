<?php

namespace App\Http\Middleware\Account;

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
        if($page === 'transfer_credit')
        {
            if(auth()->user()->isAdmin() || in_array(auth()->user()->group->id, [2,3,4])) {
                return $next($request);
            }
        }
        return redirect(route('account.profile'));
    }
}
