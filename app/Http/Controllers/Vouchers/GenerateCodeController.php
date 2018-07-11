<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Requests\Vouchers\GenerateCodeRequest;
use App\User;
use App\Voucher;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class GenerateCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.vouchers']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.vouchers.generate');
    }

    public function generate(GenerateCodeRequest $request)
    {
        $voucher = array();
        DB::beginTransaction();
        try {
            $vouchers = array();
            $v = '';
            $date_now = Carbon::now();
            for($i=0;$i<=$request->credit-1;$i++) {
                $temp = $date_now->format('y') . strtoupper(str_random(5)) . '-' . $date_now->format('m') . strtoupper(str_random(6)) . '-' . $date_now->format('d') . strtoupper(str_random(7));
                $v .= $temp . '|';
                $voucher[] = $temp;
                $vouchers[$i]['code'] = $temp;
                $vouchers[$i]['created_user_id'] = auth()->user()->id;
                $vouchers[$i]['duration'] = 2592000 + 3600;
                if(auth()->user()->isAdmin()) {
                    $vouchers[$i]['duration'] = 2592000 + 3600;
                }
                $vouchers[$i]['created_at'] = $date_now;
                //$vouchers[$i]['updated_at'] = Carbon::now();
            }
            Voucher::insert($vouchers);
            if(auth()->user()->cannot('PCODE_004')) {
                User::where('id', auth()->user()->id)->update(['credits' => (auth()->user()->credits - $request->credit)]);
            }

            DB::table('user_credit_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => auth()->user()->id,
                    'type' => 'VOUCHER',
                    'direction' => 'GENERATE',
                    'credit_used' => $request->credit,
                    'duration' => '',
                    'credit_before' => auth()->user()->credits,
                    'credit_after' => auth()->user()->can('PCODE_004') ? auth()->user()->credits : auth()->user()->credits - $request->credit,
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'You have Generated a Voucher. (Amount: '. $request->credit .')',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            redirect(route('vouchers'));
        }

        return redirect()->back()->with(['success' => 'New Code Generated.', 'vouchers' => $v]);
    }

    public function voucher_list()
    {
        $query = Voucher::FetchVoucherList(auth()->user())->with('user_from', 'user_to')->select('vouchers.*');
        return datatables()->eloquent($query)
            ->addColumn('user_from', function (Voucher $voucher) {
                return $voucher->user_from->username;
            })
            ->addColumn('user_to', function (Voucher $voucher) {
                if(auth()->user()->isAdmin()) {
                    if(!is_null($voucher->user_id)) {
                        return $voucher->user_to->username;
                    } else {
                        return '-';
                    }

                } else {
                    if($voucher->user_id == auth()->user()->id) {
                        return $voucher->user_to->username;
                    } else {
                        if($voucher->user_to->group_id > auth()->user()->group_id && ($voucher->user_to->isDownline() || auth()->user()->can('MANAGE_USER_OTHER'))) {
                            return $voucher->user_to->username;
                        } else {
                            if(!is_null($voucher->user_id)) {
                                if($voucher->user_to->username <> '###') {
                                    return '<span class="label label-' . $voucher->user_to->group->class . ' ">' . $voucher->user_to->group->name . '</span>';
                                } else {
                                    return $voucher->user_to->username;
                                }
                            } else {
                                return '-';
                            }
                        }
                    }
                }
            })
            //Carbon::parse($date_now)->addSeconds((2595600 * $request->credits) / intval($user->subscription->cost))->diffInDays() . ' Day(s)'
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
            ->rawColumns(['user_to'])
            ->make(true);
    }
}
