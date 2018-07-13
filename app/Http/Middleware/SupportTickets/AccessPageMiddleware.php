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
    public function handle($request, Closure $next, $page = '')
    {
        if(auth()->user()->isActive()) {
            if($page = 'view') {
                if(auth()->user()->cannot('MANAGE_TICKET_ID', $request->id)) {
                    return redirect(route('account.profile'));
                }
            }
            if(auth()->user()->isAdmin()) {
                return $next($request);
            }
            if(in_array(auth()->user()->group->id, [2])) {
                return $next($request);
            }
        }
        return redirect(route('account.profile'));
    }
}
