<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserEditFreezeCounterRequest;
use App\Http\Requests\ManageUsers\UserFreezeRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserVacationModeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_freeze_mode']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        return view('theme.default.manage_users.user_vacation_mode', compact('user'));
    }

    public function FreezeEnable(UserFreezeRequest $request, $id = 0)
    {
        DB::transaction(function () use ($id) {
            $user = User::findorfail($id);
            $date_now = Carbon::now();
            User::where('id', $user->id)->update([
                'freeze_start' => $date_now,
                'freeze_mode' => 1,
                'freeze_ctr' => ($user->freeze_ctr < 1 && auth()->user()->can('BYPASS_USER_FREEZE_LIMIT', $user->id)) ? $user->freeze_ctr : ($user->freeze_ctr - 1),
            ]);
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Enabled Freeze of a User.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Account Freeze was Enabled.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with('success', 'Freeze Mode Activated.');
    }

    public function FreezeDisable($id = 0)
    {
        DB::transaction(function () use ($id) {
            $user = User::findorfail($id);
            $date_now = Carbon::now();
            $new_expired_at = Carbon::parse($user->getOriginal('expired_at'));
            User::where('id', $user->id)->update([
                'expired_at' => $new_expired_at->addSeconds($user->freeze_start->diffInSeconds(Carbon::now())),
                'freeze_start' => null,
                'freeze_mode' => 0]
            );
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Disabled Freeze of a User.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Account Freeze was Disabled.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with(['success' => 'Freeze Mode Deactivated.', 'set' => 0]);
    }

    public function EditFreezeCounter(UserEditFreezeCounterRequest $request, $id = 0)
    {
        DB::transaction(function () use ($request, $id) {
            $user = User::findorfail($id);
            $date_now = Carbon::now();
            User::where('id', $user->id)->update([
                'freeze_ctr' => $request->vacation_counter,
            ]);
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Adjusted Freeze Counter of a User.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Freeze Counter was Adjusted.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with(['success' => 'Freeze Counter Updated.', 'set' => 1]);
    }
}
