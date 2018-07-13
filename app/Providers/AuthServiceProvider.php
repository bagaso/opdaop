<?php

namespace App\Providers;

use App\Post;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
            if(!$user->isActive()) {
                return false;
            }
        });

        Gate::define('UPDATE_ACCOUNT', function ($user) {
            return true;
        });

        Gate::define('UPDATE_USERNAME', function ($user) {
            if($user->isAdmin() || in_array($user->group_id, [2])) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER', function ($user) {
            if(in_array($user->group_id, [2,3,4])) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_ALL', function ($user) {
            if($user->can('MANAGE_USER_DOWNLINE') && in_array($user->group_id, [2,3,4])) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_SUB_ADMIN', function ($user) {
            return false;
        });

        Gate::define('MANAGE_USER_RESELLER', function ($user) {
            if(in_array($user->group_id, [2]) && $user->can('MANAGE_USER_DOWNLINE')) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_SUB_RESELLER', function ($user) {
            if(in_array($user->group_id, [2,3]) && $user->can('MANAGE_USER_DOWNLINE')) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_CLIENT', function ($user) {
            if(in_array($user->group_id, [2,3,4]) && $user->can('MANAGE_USER_DOWNLINE')) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_TRASH', function ($user) {
            return false;
        });

        Gate::define('MANAGE_USER_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if($data->group_id <= $user->group_id) {
                return false;
            }
            if($user->can('MANAGE_USER_DOWNLINE') && $data->isDownline()) {
                return true;
            }
            if($user->can('MANAGE_USER_OTHER') && !$data->isDownline()) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_DOWNLINE', function ($user) {
            if($user->can('MANAGE_USER')) {
                if(in_array($user->group_id, [2]) && in_array('P002', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [3]) && in_array('P052', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [4]) && in_array('P072', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('MANAGE_USER_OTHER', function ($user) {
            if($user->can('MANAGE_USER')) {
                if(in_array($user->group_id, [2]) && in_array('P003', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('CREATE_ACCOUNT', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P001', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P051', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P071', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('DELETE_USER', function ($user) {
            if($user->can('MANAGE_USER_DOWNLINE')) {
                if(in_array($user->group_id, [2]) && in_array('P004', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [3]) && in_array('P053', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [4]) && in_array('P073', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('DELETE_USER_OTHER', function ($user) {
            if($user->can('MANAGE_USER_OTHER')) {
                if(in_array($user->group_id, [2]) && in_array('P005', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('DELETE_USER_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if($user->can('DELETE_USER') && $data->isDownline()) {
                return true;
            }
            if($user->can('DELETE_USER_OTHER') && !$data->isDownline()) {
                return true;
            }
//            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P053', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P073', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
            return false;
        });

//        Gate::define('UPDATE_USER_PROFILE', function ($user, $id) {
//            $data = User::findorfail($id);
//            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P006', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P007', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P054', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P074', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            return false;
//        });

        Gate::define('MANAGE_USER_PROFILE_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if($user->can('MANAGE_USER_DOWNLINE') && $data->isDownline() && (in_array('P006', json_decode($user->permissions->pluck('code'))) || in_array('P054', json_decode($user->permissions->pluck('code'))) || in_array('P074', json_decode($user->permissions->pluck('code'))))) {
                return true;
            }
            if($user->can('MANAGE_USER_OTHER') && !$data->isDownline() && in_array('P007', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
//            if($user->can('MANAGE_USER_DOWNLINE') && $data->isDownline() && in_array('P054', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if($user->can('MANAGE_USER_DOWNLINE') && $data->isDownline() && in_array('P074', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
            return false;
        });

        Gate::define('MANAGE_USER_USERNAME_ID', function ($user, $id) {
            if($user->can('MANAGE_USER_PROFILE_ID', $id)) {
                $data = User::findorfail($id);
                if($data->isDownline() && in_array('P008', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(!$data->isDownline() && in_array('P009', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('MANAGE_USER_GROUP_ID', function ($user, $id) {
            if($user->can('MANAGE_USER_PROFILE_ID', $id)) {
                $data = User::findorfail($id);
                if($data->isDownline() && (in_array('P010', json_decode($user->permissions->pluck('code'))) || in_array('P055', json_decode($user->permissions->pluck('code'))) || in_array('P075', json_decode($user->permissions->pluck('code'))))) {
                    return true;
                }
                if(!$data->isDownline() && in_array('P011', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('MANAGE_USER_SUBSCRIPTION_ID', function ($user, $id) {
            if($user->can('MANAGE_USER_PROFILE_ID', $id)) {
                $data = User::findorfail($id);
                if($data->isDownline() && (in_array('P012', json_decode($user->permissions->pluck('code'))) || in_array('P056', json_decode($user->permissions->pluck('code'))) || in_array('P076', json_decode($user->permissions->pluck('code'))))) {
                    return true;
                }
                if(!$data->isDownline() && in_array('P013', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
//            $data = User::findorfail($id);
//            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P012', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [2]) && !$data->isDownline()&& in_array('P013', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P056', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P076', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
            return false;
        });

        Gate::define('MANAGE_USER_SECURITY_ID', function ($user, $id) {
            if($user->can('MANAGE_USER_PROFILE_ID', $id)) {
                $data = User::findorfail($id);
                if($data->isDownline() && (in_array('P014', json_decode($user->permissions->pluck('code'))) || in_array('P057', json_decode($user->permissions->pluck('code'))) || in_array('P077', json_decode($user->permissions->pluck('code'))))) {
                    return true;
                }
                if(!$data->isDownline() && in_array('P015', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
//            $data = User::findorfail($id);
//            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P014', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [2]) && !$data->isDownline()&& in_array('P015', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P057', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P077', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
            return false;
        });

        Gate::define('TRANSFER_CREDIT_DOWNLINE', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P016', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P058', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P078', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('TRANSFER_CREDIT_OTHER', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P017', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P059', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P079', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('TRANSFER_CREDIT', function ($user) {
            if($user->can('TRANSFER_CREDIT_DOWNLINE') || $user->can('TRANSFER_CREDIT_OTHER')) {
                return true;
            }
            return false;
        });

        Gate::define('TRANSFER_USER_CREDIT_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P016', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline()&& in_array('P017', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P058', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && !$data->isDownline() && in_array('P059', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P078', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && !$data->isDownline() && in_array('P079', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('UNLIMITED_CREDIT', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P018', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('SUBTRACT_CREDIT_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P019', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P020', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_VOUCHER_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P021', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P022', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P060', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && $data->isDownline() && in_array('P080', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_FREEZE', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P023', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P061', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P081', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_FREEZE_OTHER', function ($user) {
            if($user->can('MANAGE_USER_OTHER') && in_array('P024', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_FREEZE_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if($user->can('MANAGE_USER_FREEZE') && $data->isDownline()) {
                return true;
            }
            if($user->can('MANAGE_USER_FREEZE_OTHER') && !$data->isDownline()) {
                return true;
            }
//            if($user->can('UPDATE_USER_FREEZE') && $data->isDownline()) {
//                return true;
//            }
//            if($user->can('UPDATE_USER_FREEZE') && $data->isDownline()) {
//                return true;
//            }
            return false;
        });

        Gate::define('BYPASS_USER_FREEZE_LIMIT', function ($user) {
            if($user->can('MANAGE_USER_FREEZE') && in_array($user->group_id, [2]) && in_array('P025', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('BYPASS_USER_FREEZE_LIMIT_OTHER', function ($user) {
            if($user->can('MANAGE_USER_FREEZE_OTHER') && in_array('P026', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('BYPASS_USER_FREEZE_LIMIT_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if($user->can('BYPASS_USER_FREEZE_LIMIT') && $data->isDownline()) {
                return true;
            }
            if($user->can('BYPASS_USER_FREEZE_LIMIT_OTHER') && !$data->isDownline()) {
                return true;
            }
            return false;
        });

//        Gate::define('ACCESS_USER_LOGS', function ($user) {
//            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P027', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P028', json_decode($user->permissions->pluck('code')))) {
//                return true;
//            }
//            return false;
//        });

        Gate::define('ACCESS_USER_LOGS_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P027', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P028', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('ACCESS_USER_DOWNLINE_ID', function ($user, $id) {
            if($user->can('MANAGE_USER_OTHER')) {
                $data = User::findorfail($id);
                if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P029', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P030', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [3]) && $data->isDownline() && in_array('P062', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('MANAGE_USER_DURATION_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P031', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P032', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_USER_PERMISSION_ID', function ($user, $id) {
            $data = User::findorfail($id);
            if(in_array($user->group_id, [2]) && $data->isDownline() && in_array('P033', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [2]) && !$data->isDownline() && in_array('P034', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_VOUCHER', function ($user) {
            if($user->can('MANAGER_USER')) {
                if(in_array($user->group_id, [2]) && in_array('P042', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [3]) && in_array('P063', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [4]) && in_array('P082', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('MANAGE_UPDATE_JSON', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P043', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('CREATE_JSON_FILE', function ($user) {
            return false;
        });

        Gate::define('MANAGE_SERVER', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P044', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('PRIVATE_SERVER_USER_ADD_REMOVE', function ($user, $id) {
            $data = User::findorfail($id);
            if($data->isDownline() && in_array('P035', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(!$data->isDownline() && in_array('P036', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGER_POST', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P045', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_POST', function ($user, $id) {
            $post = Post::findorfail($id);
            if($user->can('MANAGER_POST') && $post->user_id == $user->id) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_SUPPORT', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P046', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('CREATE_TICKET', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P050', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P066', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P085', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [5]) && in_array('P094', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_TICKET', function ($user, $ticket_id) {
            $ticket = Ticket::findorfail($ticket_id);
            if($user->id != $ticket->user_id && in_array($user->group_id, [2])) {
                if($ticket->user->group_id > $user->group_id) {
                    if(in_array('P046', json_decode($user->permissions->pluck('code')))) {
                        return true;
                    }
                }
            }
            if($user->id == $ticket->user_id) {
                if(in_array($user->group_id, [2]) && in_array('P050', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [3]) && in_array('P066', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [4]) && in_array('P085', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
                if(in_array($user->group_id, [5]) && in_array('P094', json_decode($user->permissions->pluck('code')))) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('LOCK_TICKET', function ($user) {
            return false;
        });

        Gate::define('DELETE_TICKET', function ($user) {
            return false;
        });

        Gate::define('REMOVE_DISTRIBUTOR', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P047', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('APPLY_VOUCHER_TO_ACCOUNT', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P048', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P064', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P083', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [5]) && in_array('P093', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('ACCESS_FULL_CREDIT_LOGS', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P040', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

        Gate::define('MANAGE_ONLINE_USER', function ($user) {
            return false;
        });

        Gate::define('DISCONNECT_ONLINE_USER', function ($user) {
            return false;
        });

        Gate::define('DELETE_ONLINE_USER', function ($user) {
            return false;
        });

        Gate::define('MANAGE_PAGES', function ($user) {
            return false;
        });

        Gate::define('MANAGE_SITE_SETTINGS', function ($user) {
            return false;
        });

        Gate::define('ACCESS_SELLER_MONITOR', function ($user) {
            return false;
        });

        Gate::define('ACCOUNT_EXTEND_USING_CREDITS', function ($user) {
            if(in_array($user->group_id, [2]) && in_array('P049', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [3]) && in_array('P065', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [4]) && in_array('P084', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            if(in_array($user->group_id, [5]) && in_array('P091', json_decode($user->permissions->pluck('code')))) {
                return true;
            }
            return false;
        });

    }
}
