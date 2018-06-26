<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserCreditRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserCreditController extends Controller
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
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        return view('theme.default.manage_users.user_credit', compact('user'));
    }

    public function update(UserCreditRequest $request, $id = 0)
    {
        DB::transaction(function () use ($request, $id) {
            $user = User::findorfail($id);
            $date_now = Carbon::now();

            $old_expired_at = $user->getOriginal('expired_at');

            $duration = Carbon::parse($date_now)->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost))->diffInDays() . ' Day(s)';

            if (auth()->user()->cannot('UNLIMITED_CREDIT')) {
                User::where('id', auth()->user()->id)->update([
                    'credits' => auth()->user()->getOriginal('credits') - $request->credits,
                ]);
            }

            if($request->top_up === 'on') {
                User::where('id', $user->id)->update([
                    'expired_at' => $date_now->lt(Carbon::parse($user->getOriginal('expired_at'))) ? Carbon::parse($user->getOriginal('expired_at'))->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost)) : $date_now->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost)),
                ]);
            } else {
                User::where('id', $user->id)->update([
                    'credits' => ($user->getOriginal('credits') + $request->credits),
                ]);
            }

            $date_now = Carbon::now();

            if(in_array($user->group->id, [2,3,4]) && is_null($user->seller_first_applied_credit)) {
                User::where('id', $user->id)->update(['seller_first_applied_credit' => $date_now]);
            }

            DB::table('user_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'type' => $request->top_up ? 'TOP-UP' : 'TRANSFER-02',
                    'direction' => 'OUT',
                    'credit_used' => $request->credits,
                    'duration' => $request->top_up ? $duration : '',
                    'credit_before' => auth()->user()->credits,
                    'credit_after' => auth()->user()->credits == 'No Limit' ? auth()->user()->credits : auth()->user()->credits - $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'type' => $request->top_up ? 'TOP-UP' : 'TRANSFER-02',
                    'direction' => 'IN',
                    'credit_used' => $request->credits,
                    'duration' => $request->top_up ? $duration : '',
                    'credit_before' => $user->credits,
                    'credit_after' => $request->top_up ? $user->credits : $user->credits + $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

            DB::table('admin_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id_from' => auth()->user()->id,
                    'user_id_to' => $user->id,
                    'type' => $request->top_up ? 'TOP-UP' : 'TRANSFER-02',
                    'credit_used' => $request->credits,
                    'credit_before_from' => auth()->user()->credits,
                    'credit_after_from' => auth()->user()->credits == 'No Limit' ? auth()->user()->credits : auth()->user()->credits - $request->credits,
                    'credit_before_to' => $user->credits,
                    'credit_after_to' => $request->top_up ? $user->credits : $user->credits + $request->credits,
                    'duration' => $request->top_up ? $duration : '',
                    'duration_before' => $old_expired_at,
                    'duration_after' => $date_now->lt(Carbon::parse($old_expired_at)) ? Carbon::parse($old_expired_at)->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost)) : $date_now->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost)),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
            ]);

            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => $request->top_up ? 'You have TOP-UP a User. (Amount: ' . $request->credits . ')' : 'Credit Transferred. (Amount: '. $request->credits .')',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => $request->top_up ? 'Your Account was TOP-UP. (Amount: ' . $request->credits . ')' : 'Credit Received. (Amount: '. $request->credits .')',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

        });

        return redirect()->back()->with('success', $request->top_up === 'on' ? 'User has been successfully top-up.' : 'User Credited successfully.');
    }
}
