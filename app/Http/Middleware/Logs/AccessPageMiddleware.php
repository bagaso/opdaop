<?php

namespace App\Http\Middleware\Logs;

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
        if(auth()->user()->can('ACCESS_FULL_CREDIT_LOGS'))
        {
            return $next($request);
        }
        return redirect(route('account.profile'));
    }
}
