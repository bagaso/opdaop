<?php

namespace App\Http\Middleware\ManageUsers;

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
        if((int)auth()->user()->id === (int)$request->id) {
            return redirect(route('account.profile'));
        }
        if(!auth()->user()->isAdmin())
        {

            if($page == 'add_user') {
                if(auth()->user()->cannot('CREATE_ACCOUNT')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_all') {
                if(auth()->user()->cannot('MANAGE_USER_ALL')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_sub_admin') {
                if(auth()->user()->cannot('MANAGE_USER_SUB_ADMIN')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_reseller') {
                if(auth()->user()->cannot('MANAGE_USER_RESELLER')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_sub_reseller') {
                if(auth()->user()->cannot('MANAGE_USER_SUB_RESELLER')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_client') {
                if(auth()->user()->cannot('MANAGE_USER_CLIENT')) {
                    return redirect(route('account.profile'));
                }
            }
            if($page == 'user_list_other') {
                if(auth()->user()->cannot('MANAGE_USER_OTHER')) {
                    return redirect(route('account.profile'));
                }
            }

            if($page == 'user_profile' || $page == 'user_security' || $page == 'user_credit' || $page == 'user_voucher' || $page == 'user_freeze_mode' || $page == 'user_logs' || $page == 'user_downline' || $page == 'user_duration' || $page == 'user_permission') {
                if(auth()->user()->cannot('MANAGE_USER_ID', $request->id)) {
                    return redirect(route('account.profile'));
                }
            }

            if($page == 'user_downline') {
                if(!in_array(auth()->user()->group_id, [2,3])) {
                    return redirect(route('account.profile'));
                }
            }

            if($page == 'user_logs') {
                if(!in_array(auth()->user()->group_id, [2])) {
                    return redirect(route('account.profile'));
                }
            }

            if($page == 'user_duration') {
                if(!in_array(auth()->user()->group_id, [2])) {
                    return redirect(route('account.profile'));
                }
            }

            if($page == 'user_permission') {
                if(!in_array(auth()->user()->group_id, [2])) {
                    return redirect(route('account.profile'));
                }
            }
        }

        return $next($request);
    }
}
