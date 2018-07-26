<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserDurationRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserDurationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_duration']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        return view('theme.default.manage_users.user_duration', compact('user'));
    }

    public function update(UserDurationRequest $request, $id)
    {
        $user = User::findorfail($id);
        $date_now = Carbon::now();
        $user->expired_at = $date_now->lt(Carbon::parse($user->getOriginal('expired_at'))) ? Carbon::parse($user->getOriginal('expired_at'))->addDays($request->days)->addHours($request->hours) : $date_now->copy()->addDays($request->days)->addHours($request->hours);
        $user->save();

        if($user->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Adjusted Duration of a User.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Duration was Adjusted.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with('success', 'User Duration Updated.');
    }
}
