<?php

namespace App\Http\Controllers\ManageUsers;

use App\Group;
use App\Http\Requests\ManageUsers\AddUserRequest;
use App\Status;
use App\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:add_user']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();
        $statuses = Status::all();
        $subscriptions = Subscription::all();
        return view('theme.default.manage_users.add_user', compact('groups','statuses', 'subscriptions'));
    }

    public function create(AddUserRequest $request) {
        $current = Carbon::now();

        $new_user = new User;
        $new_user->group_id = $request->group;
        $new_user->username = strtolower($request->username);
        $new_user->password = bcrypt($request->password);
        $new_user->service_password = $request->password;
        $new_user->password_openvpn = $request->password;
        $new_user->password_ssh = $request->password;
        $new_user->value = $request->password;
        $new_user->password_ss = $request->password;
        $new_user->email = strtolower($request->email);
        $new_user->fullname = $request->fullname;
        $new_user->subscription_id = $request->subscription;
        $new_user->status_id = $request->status;
        $new_user->parent_id = auth()->user()->id;
        $new_user->consumable_data = app('settings')->data_allowance;
        $trial_type = substr(app('settings')->trial_period, -1, 1);
        $trial_interval = (int) filter_var(app('settings')->trial_period, FILTER_SANITIZE_NUMBER_INT);
        $trial_period = (($trial_type == 'h') ? $trial_interval * 3660 : 0) + (($trial_type == 'd') ? ($trial_interval * 24)  * 3660 : 0);
        $new_user->expired_at = $current->addSeconds($trial_period);
        $new_user->save();

        return redirect()->back()->with('success', 'New User Created.');
    }
}
