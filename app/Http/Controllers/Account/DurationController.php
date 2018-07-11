<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\DurationRequest;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class DurationController extends Controller
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
        return view('theme.default.account.duration');
    }

    public function update(DurationRequest $request)
    {
        DB::transaction(function () use ($request) {
            $account = User::findorfail(auth()->user()->id);
            $date_now = Carbon::now();

            $old_expired_at = $account->getOriginal('expired_at');

            User::where('id', auth()->user()->id)->update([
                'credits' => $account->cannot('UNLIMITED_CREDIT') ? $account->getOriginal('credits') - $request->credits : $account->getOriginal('credits'),
                'expired_at' => $date_now->lt(Carbon::parse($account->getOriginal('expired_at'))) ? Carbon::parse($account->getOriginal('expired_at'))->addSeconds((2595600 * $request->credits) / intval($account->subscription->cost)) : $date_now->addSeconds((2595600 * $request->credits) / intval($account->subscription->cost)),
            ]);

            $duration = Carbon::parse($date_now)->addSeconds((2595600 * $request->credits) / intval($account->subscription->cost))->diffInDays() . ' Day(s)';

            DB::table('user_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'type' => 'TOP-UP-01',
                    'direction' => 'OUT',
                    'credit_used' => $request->credits,
                    'duration' => $duration,
                    'credit_before' => $account->credits,
                    'credit_after' => $account->credits - $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'type' => 'TOP-UP-01',
                    'direction' => 'IN',
                    'credit_used' => $request->credits,
                    'duration' => $duration,
                    'credit_before' => $account->credits,
                    'credit_after' => $account->credits - $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

            DB::table('admin_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id_from' => $account->id,
                    'user_id_to' => $account->id,
                    'type' => 'TOP-UP-01',
                    'credit_used' => $request->credits,
                    'credit_before_from' => $account->credits,
                    'credit_after_from' => $account->credits - $request->credits,
                    'credit_before_to' => $account->credits,
                    'credit_after_to' => $account->credits - $request->credits,
                    'duration' => $duration,
                    'duration_before' => $old_expired_at,
                    'duration_after' => $date_now->lt(Carbon::parse($old_expired_at)) ? Carbon::parse($old_expired_at)->addSeconds((2595600 * $request->credits) / intval($account->subscription->cost)) : $date_now->copy()->addSeconds((2595600 * $request->credits) / intval($account->subscription->cost)),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
            ]);

            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have Extended your Subscription.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with('success', 'Duration Extended.');
    }
}
