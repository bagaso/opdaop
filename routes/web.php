<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/email', function () {
//    Mail::send('emails.test', [], function ($message) {
//        $message->from('admin@email.panelv4.cf', 'sample subject')
//            ->to('sample@domain.com', 'Receiver Name')
//            ->subject('sdsd');
//  });
//});

use App\HistoryVpn;
use App\OnlineUser;
use App\Server;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Auth::routes();

Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');

Route::get('/', 'Pages\IndexController@index')->name('index');
Route::get('/page/{page}', 'Pages\ContentController@index')->name('page');
Route::get('/json/{json}', 'UpdateJsons\JsonFileController@index')->name('json-update');
Route::get('/home', function () {
    return redirect(route('account.profile'));
});

Route::group(['prefix' => 'account'], function() {
    Route::get('/', 'Account\ProfileController@index')->name('account.profile');
    Route::post('/', 'Account\ProfileController@update')->name('account.profile.update');
    Route::post('/upload_photo', 'Account\ProfileController@upload')->name('account.profile.upload_photo');
    Route::post('/remove_photo', 'Account\ProfileController@remove_photo')->name('account.profile.remove_photo');

    Route::get('/security', 'Account\SecurityController@index')->name('account.security');
    Route::post('/security', 'Account\SecurityController@update')->name('account.security.update');
    Route::post('/security/service_password', 'Account\SecurityController@update_service_password')->name('account.security.update.service_password');

    Route::get('/reload', 'Account\DurationController@index')->name('account.duration');
    Route::post('/reload', 'Account\DurationController@update')->name('account.duration.update');

    Route::get('/transfer-credits', 'Account\TransferCreditController@index')->name('account.transfer_credits');
    Route::post('/transfer-credits', 'Account\TransferCreditController@update')->name('account.transfer_credits.update');

    Route::get('/apply-voucher', 'Account\VouchersController@index')->name('account.vouchers');
    Route::post('/apply-voucher', 'Account\VouchersController@apply')->name('account.vouchers.apply');
    Route::post('/applied-voucher-raw-list', 'Account\VouchersController@voucher_list')->name('account.vouchers.applied_list');

    Route::get('/vacation-mode', 'Account\VacationModeController@index')->name('account.vacation');
    Route::post('/vacation-mode-freeze-enable', 'Account\VacationModeController@FreezeEnable')->name('account.vacation.enable');
    Route::post('/vacation-mode-freeze-disable', 'Account\VacationModeController@FreezeDisable')->name('account.vacation.disable');

    Route::get('/credit-logs', 'Account\CreditLogController@index')->name('account.credit_logs');
    Route::post('/credit-logs', 'Account\CreditLogController@credit_log_list')->name('account.credit_logs.list');

    Route::get('/action-logs', 'Account\ActionLogController@index')->name('account.action_logs');
    Route::post('/action-logs', 'Account\ActionLogController@action_log_list')->name('account.action_logs.list');
});

Route::group(['prefix' => 'manage-users'], function() {
    Route::get('/add_user', 'ManageUsers\AddUserController@index')->name('manage_users.add_user');
    Route::post('/add_user', 'ManageUsers\AddUserController@create')->name('manage_users.add_user.create');

    Route::get('/', 'ManageUsers\UserListAllController@index')->name('manage_users.view_all');
    Route::post('/raw-users-list-all', 'ManageUsers\UserListAllController@user_list')->name('manage_users.user_list.all');
    Route::post('/users-list-all-delete', 'ManageUsers\UserListAllController@delete_user')->name('manage_users.user_list.all.delete');
    Route::post('/users-list-other-delete', 'ManageUsers\UserListOtherController@delete_user')->name('manage_users.user_list.other.delete');

    Route::post('/users-list-all-freeze', 'ManageUsers\UserListAllController@freeze_user')->name('manage_users.user_list.all.user_freeze');
    Route::post('/users-list-other-freeze', 'ManageUsers\UserListOtherController@freeze_user')->name('manage_users.user_list.other.user_freeze');

    Route::post('/users-list-all-unfreeze', 'ManageUsers\UserListAllController@unfreeze_user')->name('manage_users.user_list.all.user_unfreeze');
    Route::post('/users-list-other-unfreeze', 'ManageUsers\UserListOtherController@unfreeze_user')->name('manage_users.user_list.other.user_unfreeze');

    Route::get('/sub-admin', 'ManageUsers\UserListSubAdminController@index')->name('manage_users.view_sub_admin');
    Route::post('/raw-users-list-sub-admin', 'ManageUsers\UserListSubAdminController@user_list')->name('manage_users.user_list.sub_admin');

    Route::get('/reseller', 'ManageUsers\UserListResellerController@index')->name('manage_users.view_reseller');
    Route::post('/raw-users-list-reseller', 'ManageUsers\UserListResellerController@user_list')->name('manage_users.user_list.reseller');

    Route::get('/sub-reseller', 'ManageUsers\UserListSubResellerController@index')->name('manage_users.view_sub_reseller');
    Route::post('/raw-users-list-sub-reseller', 'ManageUsers\UserListSubResellerController@user_list')->name('manage_users.user_list.sub_reseller');

    Route::get('/client', 'ManageUsers\UserListClientController@index')->name('manage_users.view_client');
    Route::post('/raw-users-list-client', 'ManageUsers\UserListClientController@user_list')->name('manage_users.user_list.client');

    Route::get('/other-user', 'ManageUsers\UserListOtherController@index')->name('manage_users.view_other');
    Route::post('/raw-users-list-other', 'ManageUsers\UserListOtherController@user_list')->name('manage_users.user_list.other');

    Route::get('/trash', 'ManageUsers\UserListTrashController@index')->name('manage_users.view_trash');
    Route::post('/raw-users-list-trash', 'ManageUsers\UserListTrashController@user_list')->name('manage_users.user_list.trash');
    Route::post('/restore_user', 'ManageUsers\UserListTrashController@restore_user')->name('manage_users.restore_user');
    Route::post('/force_delete_user', 'ManageUsers\UserListTrashController@force_delete_user')->name('manage_users.force_delete_user');

    Route::get('/user/{id}', 'ManageUsers\UserProfileController@index')->name('manage_users.user_profile');
    Route::post('/user/{id}', 'ManageUsers\UserProfileController@update')->name('manage_users.user_profile.update');
    Route::post('/user/upload_photo/{id}', 'ManageUsers\UserProfileController@upload')->name('manage_users.user_profile.upload_photo');
    Route::post('/user/remove_photo/{id}', 'ManageUsers\UserProfileController@remove_photo')->name('manage_users.user_profile.remove_photo');

    Route::get('/user/{id}/security', 'ManageUsers\UserSecurityController@index')->name('manage_users.user_security');
    Route::post('/user/{id}/security', 'ManageUsers\UserSecurityController@update')->name('manage_users.user_security.update');
    Route::post('/user/{id}/security/service_password', 'ManageUsers\UserSecurityController@update_service_password')->name('manage_users.user_security.update.service_password');

    Route::get('/user/{id}/credit', 'ManageUsers\UserCreditController@index')->name('manage_users.user_credit');
    Route::post('/user/{id}/credit', 'ManageUsers\UserCreditController@update')->name('manage_users.user_credit.transfer_top_up');

    Route::get('/user/{id}/apply-voucher', 'ManageUsers\UserVoucherController@index')->name('manage_users.vouchers');
    Route::post('/user/{id}/apply-voucher', 'ManageUsers\UserVoucherController@update')->name('manage_users.vouchers.apply');
    Route::post('/user/{id}/raw-vouchers-apply', 'ManageUsers\UserVoucherController@voucher_list')->name('manage_users.vouchers.list');

    Route::get('/user/{id}/vacation-mode', 'ManageUsers\UserVacationModeController@index')->name('manage_users.user_vacation_mode');
    Route::post('/user/{id}/vacation-mode-freeze-enable', 'ManageUsers\UserVacationModeController@FreezeEnable')->name('manage_users.user_vacation_mode.enable');
    Route::post('/user/{id}/vacation-mode-freeze-disable', 'ManageUsers\UserVacationModeController@FreezeDisable')->name('manage_users.user_vacation_mode.disable');
    Route::post('/user/{id}/vacation-mode-freeze-edit', 'ManageUsers\UserVacationModeController@EditFreezeCounter')->name('manage_users.user_vacation_mode.counter_edit');

    Route::get('/user/{id}/logs', 'ManageUsers\UserLogController@index')->name('manage_users.user_log');
    Route::post('/user/{id}/raw-action-logs', 'ManageUsers\UserLogController@log_action')->name('manage_users.user_log.action');
    Route::post('/user/{id}/raw-credit-logs', 'ManageUsers\UserLogController@log_credit')->name('manage_users.user_log.credit');

    Route::get('/user/{id}/downlines', 'ManageUsers\UserDownlineController@index')->name('manage_users.user_downline');
    Route::post('/user/{id}/raw-downlines', 'ManageUsers\UserDownlineController@user_list')->name('manage_users.user_list.downline');

    Route::get('/user/{id}/duration', 'ManageUsers\UserDurationController@index')->name('manage_users.user_duration');
    Route::post('/user/{id}/duration', 'ManageUsers\UserDurationController@update')->name('manage_users.user_duration.update');

    Route::get('/user/{id}/permission', 'ManageUsers\UserPermissionController@index')->name('manage_users.user_permission');
    Route::post('/user/{id}/permission', 'ManageUsers\UserPermissionController@update')->name('manage_users.user_permission.update');
});

Route::group(['prefix' => 'generate-voucher'], function() {
    Route::get('/', 'Vouchers\GenerateCodeController@index')->name('vouchers');
    Route::post('/', 'Vouchers\GenerateCodeController@generate')->name('vouchers.generate');
    Route::post('/raw-generated-vouchers', 'Vouchers\GenerateCodeController@voucher_list')->name('vouchers.generate.list');
});

Route::group(['prefix' => 'json-file'], function() {
    Route::get('/', 'UpdateJsons\JsonListController@index')->name('json');
    Route::post('/raw-json-list', 'UpdateJsons\JsonListController@json_list')->name('json.list');
    Route::post('/delete-json', 'UpdateJsons\JsonListController@delete')->name('json.delete');

    Route::get('/create', 'UpdateJsons\AddJsonController@index')->name('json.create');
    Route::post('/create', 'UpdateJsons\AddJsonController@create')->name('json.create.do_create');

    Route::get('/edit/{id}', 'UpdateJsons\EditJsonController@index')->name('json.edit');
    Route::post('/edit/{id}', 'UpdateJsons\EditJsonController@update')->name('json.edit.do_edit');
});

Route::group(['prefix' => 'manage-servers'], function() {
    Route::get('/', 'ManageServers\ServerListController@index')->name('manage_servers');
    Route::post('/raw-servers', 'ManageServers\ServerListController@server_list')->name('manage_servers.server_list');
    Route::post('/delete', 'ManageServers\ServerListController@delete')->name('manage_servers.delete');

    Route::get('/add', 'ManageServers\ServerAddController@index')->name('manage_servers.server_add');
    Route::post('/add', 'ManageServers\ServerAddController@add')->name('manage_servers.server_add.add');

    Route::get('/server/{id}', 'ManageServers\ServerEditController@index')->name('manage_servers.server_edit');
    Route::post('/server/{id}', 'ManageServers\ServerEditController@update')->name('manage_servers.server_edit.update');
    Route::post('/server/{id}/add_user', 'ManageServers\ServerEditController@add_user')->name('manage_servers.server_edit.add_user');
    Route::post('/server/{id}/remove_user', 'ManageServers\ServerEditController@remove_user')->name('manage_servers.server_edit.remove_user');
    Route::post('/server/{id}/private_users', 'ManageServers\ServerEditController@private_userlist')->name('manage_servers.server_edit.private_users');
});

Route::group(['prefix' => 'news-and-updates'], function() {
    Route::get('/', 'NewsAndUpdates\ListController@index')->name('news_and_updates');
    Route::post('/raw-news-and-updates-list', 'NewsAndUpdates\ListController@post_list')->name('news_and_updates.raw_list');
    Route::post('/delete', 'NewsAndUpdates\ListController@delete')->name('news_and_updates.delete');

    Route::get('/create', 'NewsAndUpdates\CreatePostController@index')->name('news_and_updates.create');
    Route::post('/create', 'NewsAndUpdates\CreatePostController@create')->name('news_and_updates.create.do_create');

    Route::get('/post/edit/{id}', 'NewsAndUpdates\EditPostController@index')->name('news_and_updates.edit');
    Route::post('/post/edit/{id}', 'NewsAndUpdates\EditPostController@update')->name('news_and_updates.edit.do_edit');

    Route::get('/post/{id}/{title}', 'NewsAndUpdates\ViewPostController@index')->name('news_and_updates.view');
});

Route::group(['prefix' => 'support'], function() {
    Route::get('/', 'SupportTickets\ListController@index')->name('support_tickets');
    Route::post('/raw-support-ticket-list', 'SupportTickets\ListController@ticket_list')->name('support_tickets.list');
    Route::post('/tickets-open', 'SupportTickets\ListController@multi_open')->name('support_tickets.multi_open');
    Route::post('/tickets-close', 'SupportTickets\ListController@multi_close')->name('support_tickets.multi_close');
    Route::post('/tickets-lock', 'SupportTickets\ListController@multi_lock')->name('support_tickets.multi_lock');
    Route::post('/tickets-unlock', 'SupportTickets\ListController@multi_unlock')->name('support_tickets.multi_unlock');

    Route::get('/open', 'SupportTickets\TicketListOpenController@index')->name('support_tickets.open');
    Route::post('/raw-support-ticket-list-open', 'SupportTickets\TicketListOpenController@ticket_list')->name('support_tickets.list.open');

    Route::get('/close', 'SupportTickets\TicketListCloseController@index')->name('support_tickets.close');
    Route::post('/raw-support-ticket-list-close', 'SupportTickets\TicketListCloseController@ticket_list')->name('support_tickets.list.close');

    Route::get('/lock', 'SupportTickets\TicketListLockController@index')->name('support_tickets.lock');
    Route::post('/raw-support-ticket-list-lock', 'SupportTickets\TicketListLockController@ticket_list')->name('support_tickets.list.lock');

    Route::get('/create_ticket', 'SupportTickets\CreateTicketController@index')->name('support_tickets.create_ticket');
    Route::post('/create_ticket', 'SupportTickets\CreateTicketController@create_ticket')->name('support_tickets.create_ticket.create');

    Route::get('/ticket_id/{id}', 'SupportTickets\ViewTicketController@index')->name('support_tickets.view_ticket');
    Route::post('/ticket_id/{id}', 'SupportTickets\ViewTicketController@reply')->name('support_tickets.view_ticket.reply');
    Route::post('/ticket_id/{id}/close', 'SupportTickets\ViewTicketController@close')->name('support_tickets.view_ticket.close');
    Route::post('/ticket_id/{id}/lock', 'SupportTickets\ViewTicketController@lock')->name('support_tickets.view_ticket.lock');
    Route::post('/ticket_id/{id}/unlock', 'SupportTickets\ViewTicketController@unlock')->name('support_tickets.view_ticket.unlock');

    Route::post('/delete_ticket', 'SupportTickets\ListController@delete')->name('support_tickets.delete');
});

Route::group(['prefix' => 'authorized-reseller'], function() {
    Route::get('/', 'AuthorizedResellers\ListController@index')->name('authorized_reseller');
    Route::post('/raw-authorized-reseller-list', 'AuthorizedResellers\ListController@reseller_list')->name('authorized_reseller.list');
    Route::post('/remove', 'AuthorizedResellers\ListController@remove')->name('authorized_reseller.remove');
});

Route::group(['prefix' => 'pages'], function() {
    Route::get('/', 'Pages\PageListController@index')->name('pages');
    Route::post('/raw-pages-list', 'Pages\PageListController@page_list')->name('pages.list');
    Route::post('/delete', 'Pages\PageListController@delete')->name('pages.delete');

    Route::get('/add-page', 'Pages\AddPageController@index')->name('pages.add');
    Route::post('/add-page', 'Pages\AddPageController@create')->name('pages.add.do_add');

    Route::get('/edit/{id}', 'Pages\EditPageController@index')->name('pages.edit');
    Route::post('/edit/{id}', 'Pages\EditPageController@update')->name('pages.edit.do_edit');

    Route::get('/system-page', 'Pages\SystemPageController@index')->name('pages.system_page');
    Route::post('/system-page', 'Pages\SystemPageController@update')->name('pages.system_page.update');
});

Route::group(['prefix' => 'online-users'], function() {
    Route::get('/', 'OnlineUsers\OnlineListController@index')->name('online_users');
    Route::post('/raw-online-users-list', 'OnlineUsers\OnlineListController@online_list')->name('online_users.vpn_online_list');
    Route::post('/session-disconnect', 'OnlineUsers\OnlineListController@disconnect')->name('online_users.vpn_online_list.disconnect');
    Route::post('/force-delete-user', 'OnlineUsers\OnlineListController@force_delete_user')->name('online_users.vpn_online_list.force_delete');
});

Route::group(['prefix' => 'logs'], function() {
    Route::get('/', 'Logs\FullCreditLogController@index')->name('logs');

    Route::get('/credits', 'Logs\FullCreditLogController@index')->name('logs.credit');
    Route::post('/raw-credits-list', 'Logs\FullCreditLogController@log_list')->name('logs.credit.list');
});

Route::group(['prefix' => 'settings'], function() {
    Route::get('/', 'Settings\SettingsController@index')->name('settings');
    Route::post('/', 'Settings\SettingsController@update')->name('settings.update');
});

Route::group(['prefix' => 'seller-summary'], function() {
    Route::get('/', 'SellerSummary\SellerRenewSummaryController@index')->name('seller_summary.renew');
    Route::post('/renew_summary_list', 'SellerSummary\SellerRenewSummaryController@renew_summary_list')->name('seller_summary.renew_summary_list');
});


Route::get('/openvpn_auth', function (Request $request) {
    try {
        $username = $request->username;
        $password = $request->password;
        #$server_key = $request->server_key;

        if($username == '' || $password == '') return '0';

        if (!preg_match("/^[a-z0-9_]+$/", $username)) {
            Log::info('AUTH_FAILED CAPS: ' . $username);
            return '0';
        }


        $account = User::where('username', $username)->firstorfail();

        if($account->password_openvpn == $password) {
            return '1';
        }

        Log::info('AUTH_FAILED: ' . $username);
        return '0';
    } catch (ModelNotFoundException $ex) {
        Log::info('AUTH_FAILED: ' . $request->username);
        return '0';
    }
});

//Route::get('/sample', function () {
//    $server = Server::findorfail(5);
//    $users = $server->online_users;
//    return $users;
//});

Route::get('/openvpn_connect', function (Request $request) {
    try {
        $username = trim($request->username);
        $server_key = trim($request->server_key);

        if($username == '' || $server_key == '') return '0';

        $account = User::where('username', $username)->firstorfail();
        $server = Server::where('server_key', $server_key)->firstorfail();

        if($account->vpn()->where('server_id', $server->id)->exists()) {
            Log::info('You have active device on ' . $server->server_name . ' server: ' . $account->username);
            return 'You have active device on ' . $server->server_name . ' server.';
        }

        if(!$account->isAdmin()) {

            #$current = Carbon::now();
            #$dt = Carbon::parse($account->getOriginal('expired_at'));

            if(!$server->is_active || !$server->server_access->is_active) {
                if(!$server->is_active) {
                    Log::info('Server ' . $server->server_name . ' is down: ' . $username);
                    return 'Server ' . $server->server_name . ' is down.';
                }
                if(!$server->server_access->is_active) {
                    Log::info('Server ' . $server->server_name . ' access on ' . $server->server_access->name . ' is disabled: ' . $username);
                    return 'Server ' . $server->server_name . ' access on ' . $server->server_access->name . ' is disabled.';
                }
            }

            if(!$account->f_login_openvpn && !$account->subscription->login_openvpn) {
                Log::info('OpenVPN login is disabled for ' . $account->subscription->name . ' subscription: ' . $username);
                return 'OpenVPN login is disabled for ' . $account->subscription->name . ' subscription.';
            }

            if(!$account->subscription->is_enable) {
                Log::info($account->subscription->name . ' subscription is not enabled: ' . $username);
                return $account->subscription->name . ' subscription is not enabled.';
            }

            if(!in_array($account->subscription->id, json_decode($server->subscriptions->pluck('id')))) {
                Log::info($account->subscription->name . ' subscription is not allowed in this server: ' . $username);
                return $account->subscription->name . ' subscription is not allowed in this server.';
            }

            if(!$account->isActive()) {
                Log::info('Account is not activated: ' . $username);
                return 'Account is not activated.';
            }

            if($account->freeze_mode) {
                Log::info('Account is in freeze mode: ' . $username);
                return 'Account is in freeze mode.';
            }

            if($server->limit_bandwidth && $account->consumable_data <= 0) {
                Log::info('You used all data allocated: ' . $username);
                return 'You used all data allocated.';
            }

            if(!$server->server_access->is_paid) {
                if(!$account->freeSubscription()) {
                    Log::info('Paid user cannot enter free server: ' . $username);
                    return 'Paid user cannot enter free server.';
                }

                $free_servers = Server::FreeServerOpenvpn()->get();
                $free_ctr = 0;
                foreach ($free_servers as $free) {
                    if($free->online_users()->where('user_id', $account->id)->count() > 0) {
                        $free_ctr += 1;
                    }
                }

                if($free_ctr > 0) {
                    Log::info('Only one device allowed for free user on a free server: ' . $username);
                    return 'Only one device allowed for free user on a free server.';
                }
            }

            if($server->server_access->is_paid) {
                if(!$account->paidSubscription()) {
                    Log::info('Account is already expired: ' . $username);
                    return 'Account is already expired.';
                }

                $normal_servers = Server::NormalServerOpenvpn()->get();
                $normal_server_sessions = 0;
                foreach ($normal_servers as $normal_server) {
                    if($normal_server->online_users()->where('user_id', $account->id)->count() > 0) {
                        $normal_server_sessions++;
                    }
                }

                $special_servers = Server::SpecialServerOpenvpn()->get();
                $special_server_sessions = 0;
                foreach ($special_servers as $special_server) {
                    if($special_server->online_users()->where('user_id', $account->id)->count() > 0) {
                        $special_server_sessions++;
                    }
                }

                if($server->isSpecialServer() && $special_server_sessions >= 1) {
                    Log::info('1-Max device reached on ' . strtolower($server->server_access->name) . ' Server: ' . $username);
                    return 'Max device reached on ' . strtolower($server->server_access->name) . ' Server.';
                }

                if($account->normalSubscription() && $normal_server_sessions >= 1) {
                    Log::info('2-Max device reached on ' . strtolower($server->server_access->name) . ' Server: ' . $username);
                    return 'Max device reached on ' . strtolower($server->server_access->name) . ' Server.';
                }

                if($account->specialSubscription() && ($normal_server_sessions + $special_server_sessions) >= $account->subscription->device) {
                    Log::info('3-Max device reached on ' . strtolower($server->server_access->name) . ' Server: ' . $username);
                    return 'Max device reached on ' . strtolower($server->server_access->name) . ' Server.';
                }
            }

            if($server->server_access->is_private) {
                if(!in_array($account->id, json_decode($server->privateUsers->pluck('id')))) {
                    Log::info('Your account is not allowed to login to ' . strtolower($server->server_access->name) . ' server: ' . $username);
                    return 'Your account is not allowed to login to ' . strtolower($server->server_access->name) . ' server.';
                }
            }
        }

        $vpn = new OnlineUser;
        $vpn->user_id = $account->id;
        $vpn->user_ip = $request->trusted_ip ? $request->trusted_ip : '0.0.0.0';
        $vpn->user_port = $request->trusted_port ? $request->trusted_port : '0';
        $vpn->protocol = 'OpenVPN';
        $vpn->server_id = $server->id;
        $vpn->byte_sent = 0;
        $vpn->byte_received = 0;
        $vpn->data_available = $account->getOriginal('consumable_data');
        if($vpn->save()) {
            $dl_speed = $account->dl_speed_openvpn ? $account->dl_speed_openvpn : '0kbit';
            $up_speed = $account->up_speed_openvpn ? $account->up_speed_openvpn : '0kbit';
            $dl_speed = $dl_speed == '0kbit' ? '150mbit' : $dl_speed;
            $up_speed = $up_speed == '0kbit' ? '150mbit' : $up_speed;
            return '1;' . $dl_speed  . ';' . $up_speed;
        }

        return 'Server Error.';

    } catch (ModelNotFoundException $ex) {
        return '0';
    }
});

Route::get('/openvpn_disconnect', function (Request $request) {
    try {
        $username = trim($request->username);
        $server_key = trim($request->server_key);
        $bytes_sent = trim($request->bytes_sent);
        $bytes_received = trim($request->bytes_received);

        $server = Server::where('server_key', $server_key)->firstorfail();
        $account = User::where('username', $username)->firstorfail();

        $account->timestamps = false;
        $account->lifetime_bandwidth = doubleval($account->lifetime_bandwidth) + doubleval($bytes_sent);

        $vpn_session = $account->vpn()->where('server_id', $server->id)->firstorfail();

        if(!$account->isAdmin() && $server->limit_bandwidth) {
            $data = doubleval($vpn_session->data_available) - doubleval($bytes_sent);
            $account->consumable_data = ($data >= 0) ? $data : 0;
        }

        $account->save();

        $vpn_history = new HistoryVpn;
        $vpn_history->user_id = $account->id;
        $vpn_history->protocol = $vpn_session->protocol;
        $vpn_history->user_ip = $vpn_session->user_ip;
        $vpn_history->user_port = $vpn_session->user_port;
        $vpn_history->server_name = $server->server_name;
        $vpn_history->server_ip = $server->server_ip;
        $vpn_history->sub_domain = $server->sub_domain;
        $vpn_history->byte_sent = floatval($bytes_sent);
        $vpn_history->byte_received = floatval($bytes_received);
        $vpn_history->session_start = Carbon::parse($vpn_session->getOriginal('created_at'));
        $vpn_history->session_end = Carbon::now();
        $vpn_history->save();

        $vpn_session->delete();

        return '1';

    } catch (ModelNotFoundException $ex) {
        return '0';
    }
});

Route::get('/server-status', function () {
    try {
        $servers = Server::all();
        $list = [];
        $ctr = 0;
        foreach ($servers as $server) {
            $list['Server Status'][$ctr]['Server'] = $server->server_name;
            $list['Server Status'][$ctr]['Limit'] = $server->limit_bandwidth ? 'Yes' : 'No';
            $list['Server Status'][$ctr]['Status'] = $server->is_active ? 'Online' : 'Offline';
            $ctr++;
        }
        return response()->json($list, 200);
    } catch (ModelNotFoundException $ex) {
        return '{"":""}';
    }
});

Route::get('/duration/pc/{username}', function($username) {
    $account = User::where('username', $username)->firstorfail();

    return response()->json([
        'duration' => $account->expired_at
    ], 200);
});

Route::get('/duration/android/{username}', function($username) {
    $account = \App\User::where('username', $username)->firstorfail();

    if($account->expired_at == 'No Limit') {
        return response()->json([
            'premium' => -1,
            'vip' => null,
        ], 200);
    } else {
        return response()->json([
            'premium' => Carbon::now()->gte(Carbon::parse($account->getOriginal('expired_at'))) ? 0 : Carbon::parse($account->getOriginal('expired_at'))->diffInSeconds(Carbon::now()),
            'vip' => null,
        ], 200);
    }
});

Route::get('/contact/android/{username}', function($username) {
    $account = \App\User::where('username', $username)->firstorfail();

    $upline = \App\User::findorfail($account->upline->id);

    return response()->json([
        'fullname' => $upline->fullname,
        'email' => $upline->email,
        'contact' => $upline->contact,
    ], 200);
});

