<?php

namespace App\Http\Middleware\SupportTickets;

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
        if(auth()->user()->cannot('MANAGE_TICKET', $request->id)) {
            return redirect(route('account.profile'));
        }
        return $next($request);
    }
}
