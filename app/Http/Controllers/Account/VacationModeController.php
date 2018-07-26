<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\FreezeRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class VacationModeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.account.vacation_mode');
    }

    public function FreezeEnable(FreezeRequest $request)
    {
        DB::transaction(function () {
            $account = User::findorfail(auth()->user()->id);
            $date_now = Carbon::now();
            User::where('id', $account->id)->update(['freeze_start' => $date_now, 'freeze_ctr' => ($account->can('PCODE_018') ? $account->freeze_ctr : $account->freeze_ctr - 1), 'freeze_mode' => 1]);
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have Enabled Freeze.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }, 5);
        return redirect()->back()->with('success', 'Vacation Mode Activated.');
    }

    public function FreezeDisable()
    {
        DB::transaction(function () {
            $account = User::findorfail(auth()->user()->id);
            $date_now = Carbon::now();
            $new_expired_at = Carbon::parse($account->getOriginal('expired_at'));
            User::where('id', auth()->user()->id)->update(['expired_at' => $new_expired_at->addSeconds($account->freeze_start->diffInSeconds($date_now)), 'freeze_start' => null, 'freeze_mode' => 0]);
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have disabled freeze.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with('success', 'Vacation Mode Deactivated.');
    }
}
