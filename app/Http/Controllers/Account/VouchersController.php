<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\AppliedVoucherSearchRequest;
use App\Http\Requests\Account\ApplyVoucherRequest;
use App\User;
use App\Voucher;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class VouchersController extends Controller
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
        return view('theme.default.account.vouchers');
    }

    public function apply(ApplyVoucherRequest $request)
    {
        DB::transaction(function () use ($request) {
            $date_now = Carbon::now();
            Voucher::where('code', $request->voucher)->update([
                'user_id' => auth()->user()->id,
                'updated_at' => $date_now
            ]);
            $expired_at = Carbon::parse(auth()->user()->getOriginal('expired_at'));
            if($date_now->lt($expired_at)) {
                $new_expired_at = $expired_at->addSeconds(2595600 / intval(auth()->user()->subscription->cost));
            } else {
                $new_expired_at = $date_now->copy()->addSeconds(2595600  / intval(auth()->user()->subscription->cost));
            }
            User::where('id', auth()->user()->id)->update([
                'expired_at' => $new_expired_at,
            ]);

            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'You have applied voucher to your account.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        });
        return redirect()->back()->with('success', 'Voucher Applied.');
    }

    public function voucher_list(AppliedVoucherSearchRequest $request)
    {
        $query = Voucher::FetchApplyVoucherList(auth()->user())->with('user_from')->select('vouchers.*');
        return datatables()->eloquent($query)
            ->addColumn('user_from', function (Voucher $voucher) {
                if(auth()->user()->isAdmin()) {
                    return $voucher->user_from->username;
                } else {
                    if($voucher->created_user_id == auth()->user()->id) {
                        return $voucher->user_from->username;
                    } else {
                        if(auth()->user()->group_id < $voucher->user_from->group_id) {
                            return $voucher->user_from->username;
                        } else {
                            if($voucher->user_from->username <> '###') {
                                return '<span class="label label-' . $voucher->user_from->group->class . ' ">' . $voucher->user_from->group->name . '</span>';
                            } else {
                                return $voucher->user_from->username;
                            }
                        }
                    }
                }
            })
            ->editColumn('duration', function (Voucher $voucher) {
                $date_now = Carbon::now();
                return Carbon::parse($date_now)->addSeconds($voucher->duration)->diffInDays() . ' Day(s)';
            })
            ->editColumn('updated_at', function (Voucher $voucher) {
                return $voucher->updated_at ? $voucher->updated_at->format('Y-m-d') : '-' ;
            })
            ->editColumn('created_at', function (Voucher $voucher) {
                return $voucher->created_at ? $voucher->created_at->format('Y-m-d') : '-' ;
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(updated_at,'%Y-%m-%d') like ?", ["%$keyword%"]);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['user_from'])
            ->make(true);
    }
}
