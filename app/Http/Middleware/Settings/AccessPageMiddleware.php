<?php

namespace App\Http\Middleware\Settings;

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
        if(auth()->user()->can('MANAGE_SITE_SETTINGS')) {
            return $next($request);
        }
        return redirect(route('account.profile'));
    }
}
