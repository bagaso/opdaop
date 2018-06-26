<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Requests\Vouchers\ApplyCodeRequest;
use App\User;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplyVoucherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.vouchers:apply']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.vouchers.apply');
    }

    public function update(ApplyCodeRequest $request)
    {
        DB::transaction(function () use ($request) {
            Voucher::where('code', $request->voucher)->update([
                'user_id' => auth()->user()->id,
            ]);
            $current = Carbon::now();
            $expired_at = Carbon::parse(auth()->user()->getOriginal('expired_at'));
            if($current->lt($expired_at)) {
                $new_expired_at = $expired_at->addSeconds(2595600 / intval(auth()->user()->subscription->cost));
            } else {
                $new_expired_at = $current->addSeconds(2595600  / intval(auth()->user()->subscription->cost));
            }
            User::where('id', auth()->user()->id)->update([
                'expired_at' => $new_expired_at,
            ]);
        });
        return redirect()->back()->with('success', 'Voucher Applied.');
    }

    public function voucher_list()
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
