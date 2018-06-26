<?php

namespace App\Http\Middleware\UpdateJsons;

use App\UpdateJson;
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
            if(!in_array(auth()->user()->group->id, [2])) {
                return redirect(route('account.profile'));
            }
//            $update_json = UpdateJson::findorfail($request->id);
//            if(!$update_json->is_enable) {
//                return redirect(route('account.profile'));
//            }
        }
        return $next($request);
    }
}
