<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.settings']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::findorfail(1);
        return view('theme.default.settings.settings', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $settings = Setting::findorfail(1);
        $settings->site_name = $request->site_name;
        $settings->site_url = $request->site_url;
        $settings->maintenance_mode = $request->site_maintenance_mode;
        $settings->enable_backup = $request->backup;
        $settings->backup_cron = $request->backup_cron;
        $settings->trial_period = $request->trial_period;
        $settings->enable_data_reset = $request->data_reset;
        $settings->data_reset_cron = $request->data_reset_cron;
        $settings->data_allowance = $request->data_allowance;
        $settings->enable_authorized_reseller = $request->enable_authorized_reseller;
        $settings->public_authorized_reseller = $request->public_authorized_reseller;
        $settings->enable_server_status = $request->enable_server_status;
        $settings->public_server_status = $request->public_server_status;
        $settings->enable_online_users = $request->enable_online_users;
        $settings->public_online_users = $request->public_online_users;
        $settings->max_credit_transfer = $request->max_credit_transfer;
        $settings->renewal_qualified = $request->renewal_qualified;
        $settings->save();
        return redirect()->back()->with('success', 'Site Settings Updated.');
    }
}
