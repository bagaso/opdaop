<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserSecurityRequest;
use App\Http\Requests\ManageUsers\UserServicePasswordRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserSecurityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_security']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        return view('theme.default.manage_users.user_security', compact('user'));
    }

    public function update(UserSecurityRequest $request, $id = 0)
    {
        $user = User::findorfail($id);
        $date_now = Carbon::now();
        if(!Hash::check($request->new_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
        }
        $user->save();

        if($user->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Updated User Password.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Password was Updated.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'User Security Updated.', 'set' => 0]);
    }

    public function update_service_password(UserServicePasswordRequest $request, $id = 0)
    {
        $user = User::findorfail($id);
        $date_now = Carbon::now();
        if($request->service_password != $user->service_password) {
            $user->service_password = $request->service_password; //general password
            $user->password_openvpn = $request->service_password; //openvpn password
            $user->password_ssh = crypt($request->service_password, config('app.key')); //ssh password
            $user->value = $request->service_password; //softether password
            $user->password_ss = $request->service_password; //ss password
        }
        $user->save();

        if($user->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Updated User Service Password.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Service Password was Updated.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'User Service Password Updated.', 'set' => 1]);
    }
}
