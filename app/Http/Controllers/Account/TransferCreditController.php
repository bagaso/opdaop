<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\TransferCreditRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class TransferCreditController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.account:transfer_credit']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.account.quick_transfer');
    }

    public function update(TransferCreditRequest $request)
    {
        DB::transaction(function () use ($request) {
            if(auth()->user()->cannot('UNLIMITED_CREDIT')) {
                User::where('id', auth()->user()->id)->update([
                    'credits' => auth()->user()->getOriginal('credits') - $request->credits,
                ]);
            }
            $user = User::where('username', $request->username)->first();
            User::where('id', $user->id)->update([
                'credits' => $user->getOriginal('credits') + $request->credits,
            ]);

            $date_now = Carbon::now();

            DB::table('user_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'type' => 'TRANSFER-01',
                    'direction' => 'OUT',
                    'credit_used' => $request->credits,
                    'duration' => '',
                    'credit_before' => auth()->user()->credits,
                    'credit_after' => auth()->user()->credits == 'No Limit' ? auth()->user()->credits : auth()->user()->credits - $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'type' => 'TRANSFER-01',
                    'direction' => 'IN',
                    'credit_used' => $request->credits,
                    'duration' => '',
                    'credit_before' => $user->credits,
                    'credit_after' => $user->credits + $request->credits,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

            DB::table('admin_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id_from' => auth()->user()->id,
                    'user_id_to' => $user->id,
                    'type' => 'TRANSFER-01',
                    'credit_used' => $request->credits,
                    'credit_before_from' => auth()->user()->credits,
                    'credit_after_from' => auth()->user()->credits == 'No Limit' ? auth()->user()->credits : auth()->user()->credits - $request->credits,
                    'credit_before_to' => $user->credits,
                    'credit_after_to' => $user->credits + $request->credits,
                    'duration' => '',
                    'duration_before' => Carbon::parse($user->getOriginal('expired_at')),
                    'duration_after' => Carbon::parse($user->getOriginal('expired_at')),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
            ]);

            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'Credit transferred. (Amount: '. $request->credits .')',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Credit received. (Amount: '. $request->credits .')',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

        });
        return redirect()->back()->with('success', 'Credit Transferred ('. $request->username .' +' . $request->credits. ' credits)');
    }
}
