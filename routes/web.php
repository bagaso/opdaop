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

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

Route::get('/email', function () {
    Mail::send('emails.test', [], function ($message) {
        $message->from('admin@email.panelv4.cf', 'VPN Panel v4.0')
            ->to('mp3sniff@gmail.comn', 'Receiver Name')
            ->subject('sdsd');
  });
});

Auth::routes();

Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');

Route::get('/files', function () {
    $files = Storage::disk('s3')->files('VPN-Panel/');
    //return $files;
    foreach ($files as $file) {
        //echo $file . '</br>';
        $file_dt = Carbon::createFromTimestamp(Storage::disk('s3')->lastModified($file));
        $dt = Carbon::now();
        echo $file  . ' - ' . $dt->diffInDays($file_dt) . '</br>';
        $ctr = 0;
        if($dt->diffInDays($file_dt) == 2) {
            Storage::delete($file);
            $ctr++;
        }
        echo $ctr . ' Files Deleted.';
    }
})->name('files');

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
    Route::post('/users-restore', 'ManageUsers\UserListTrashController@restore_user')->name('manage_users.user_restore');

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

Route::group(['prefix' => 'vouchers'], function() {
    Route::get('/', 'Vouchers\GenerateCodeController@index')->name('vouchers');
    Route::post('/', 'Vouchers\GenerateCodeController@generate')->name('vouchers.generate');
    Route::post('/raw-vouchers', 'Vouchers\GenerateCodeController@voucher_list')->name('vouchers.generate.list');

    Route::get('/apply', 'Vouchers\ApplyVoucherController@index')->name('vouchers.apply');
    Route::post('/apply', 'Vouchers\ApplyVoucherController@update')->name('vouchers.apply.do_apply');
    Route::post('/raw-vouchers-applied', 'Vouchers\ApplyVoucherController@voucher_list')->name('vouchers.apply.list');
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
    Route::post('/vpn-disconnect', 'OnlineUsers\OnlineListController@remove')->name('online_users.vpn_online_list.disconnect');
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