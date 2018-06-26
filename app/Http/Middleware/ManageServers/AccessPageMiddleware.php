<?php

namespace App\Http\Middleware\ManageServers;

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
            if(!in_array(auth()->user()->group_id, [2])) {
                return redirect(route('account.profile'));
            }
        }
        return $next($request);
    }
}
