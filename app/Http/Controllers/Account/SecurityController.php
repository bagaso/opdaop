<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\SecurityRequest;
use App\Http\Requests\Account\ServicePasswordRequest;
use App\User;
use App\UserActionLog;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class SecurityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.account.security');
    }

    public function update(SecurityRequest $request)
    {
        $account = User::findorfail(auth()->user()->id);
        $date_now = Carbon::now();
        if(!Hash::check($request->new_password, $account->password)) {
            $account->password = bcrypt($request->new_password);
        }
        $account->save();

        if($account->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have Updated your Password.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'Security Updated.', 'set' => 0]);
    }

    public function update_service_password(ServicePasswordRequest $request)
    {
        $account = User::findorfail(auth()->user()->id);
        $date_now = Carbon::now();
        if($request->service_password != $account->service_password) {
            $account->service_password = $request->service_password; //general password
            $account->password_openvpn = $request->service_password; //openvpn password
            $account->password_ssh = $request->service_password; //openvpn password
            $account->value = $request->service_password; //softether password
        }
        $account->save();

        if($account->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have Updated your Service Password.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'Service Password Updated.', 'set' => 1]);
    }
}
