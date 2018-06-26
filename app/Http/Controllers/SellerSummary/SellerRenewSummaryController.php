<?php

namespace App\Http\Controllers\SellerSummary;

use App\Http\Requests\SellerSummary\SearchSellerSummaryRequest;
use App\User;
use App\Http\Controllers\Controller;

class SellerRenewSummaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.seller_monitor']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.seller_summary.renew_summary');
    }

    public function renew_summary_list(SearchSellerSummaryRequest $request)
    {
//        $query = UserCreditLog::with('user', 'user_related')
//            ->selectRaw('user_credit_logs.id, user_credit_logs.user_id, user_credit_logs.user_id_related, user_credit_logs.credit_used, MAX(user_credit_logs.created_at) as created_at')
//            ->join(DB::raw('(select id, user_id, user_id_related, credit_used, max(created_at) AS created_at from user_credit_logs GROUP BY id ORDER BY created_at desc) as sub'), function ($join) {
//                $join->on('user_credit_logs.id', '=', 'sub.id')
//                    ->on('user_credit_logs.credit_used', '=', 'sub.credit_used')
//                    ->on('user_credit_logs.created_at', '=', 'sub.created_at');
//            })
//            ->whereHas('user', function ($query) {
//                $query->whereIn('group_id', [2,3,4]);
//            })
//            ->where('direction', 'IN')
//            ->whereIn('type', ['TRANSFER-01', 'TRANSFER-02'])
//            ->groupBy(['user_id']);

//        $query = UserCreditLog::with('user', 'user_related')
//            ->selectRaw('user_credit_logs.id, user_credit_logs.user_id, user_credit_logs.user_id_related, user_credit_logs.credit_used, user_credit_logs.created_at as created_at')
//            ->sortByDesc('user_credit_logs.created_at')
//            ->whereHas('user', function ($query) {
//                $query->whereIn('group_id', [2,3,4]);
//            })
//            ->where('direction', 'IN');
//
//        return datatables()->eloquent($query)
//            ->addColumn('user', function (UserCreditLog $UserCreditLog) {
//                return (!auth()->user()->isAdmin() && $UserCreditLog->user->isAdmin()) ? 'Hidden' : $UserCreditLog->user->username;
//            })
//            ->addColumn('group', function (UserCreditLog $UserCreditLog) {
//                return '<span class="label label-' . $UserCreditLog->user->group->class . '">' . $UserCreditLog->user->group->name . '</span>';
//            })
//            ->addColumn('upline', function (UserCreditLog $UserCreditLog) {
//                return (!auth()->user()->isAdmin() && $UserCreditLog->user->upline->isAdmin()) ? 'Hidden' : $UserCreditLog->user->upline->username;
//            })
//            ->addColumn('user_related', function (UserCreditLog $UserCreditLog) {
//                return (!auth()->user()->isAdmin() && $UserCreditLog->user_related->isAdmin()) ? 'Hidden' : $UserCreditLog->user_related->username;
//            })
//            ->addColumn('datetime_last_renewed', function (UserCreditLog $UserCreditLog) {
//                return $UserCreditLog->created_at->diffForHumans();
//            })
////            ->editColumn('credit', function (UserCreditLog $UserCreditLog) {
////                $credit = DB::table('user_credit_logs')
////                    ->where('created_at', $UserCreditLog->created_at)
////                    ->where('user_id', $UserCreditLog->user_id)
////                    ->where('user_id_related', $UserCreditLog->user_id_related)->first();
////                return $credit->credit_used;
////            })
//            ->addColumn('datetime_first_applied', function (UserCreditLog $UserCreditLog) {
//                return $UserCreditLog->user->seller_first_applied_credit;
//            })
//            ->addColumn('renewal_qualified', function (UserCreditLog $UserCreditLog) {
//                return ($UserCreditLog->credit_used >= app('settings')->renewal_qualified && $UserCreditLog->created_at->diffIndays() <= app('settings')->max_time_lapse_renewal) ? '<i class="fa fa-fw fa-check-circle" style="color: #1e8011; text-align: center;"></i>' : '<i class="fa fa-fw fa-times-circle" style="color: #80100c; text-align: center;"></i>';
//            })
//            ->blacklist(['group', 'credit', 'datetime_last_renewed', 'upline', 'datetime_first_applied', 'renewal_qualified'])
//            ->rawColumns(['group', 'renewal_qualified'])
//            ->make(true);

        $query = User::with('group', 'upline', 'previousMonthRenew', 'previousMonthRenew.user_related')
            ->selectRaw('users.id, users.username, users.group_id, users.parent_id, users.seller_first_applied_credit')
            ->whereIn('group_id', [2,3,4])
            ->where('status_id', '=', 2)
            ->orderBy('username');
        return datatables()->eloquent($query)
            ->addColumn('group', function (User $user) {
                return '<span class="label label-' . $user->group->class . '">' . $user->group->name . '</span>';
            })
            ->addColumn('upline', function (User $user) {
                return $user->upline->username;
            })
            ->addColumn('credit_accumulated', function (User $user) {
                return ($user->previousMonthRenew->sum('credit_used') >= app('settings')->renewal_qualified) ? ($user->previousMonthRenew->sum('credit_used')) : ($user->currentMonthRenew->sum('credit_used'));
            })
            ->addColumn('status', function (User $user) {
                return (($user->previousMonthRenew->sum('credit_used') >= app('settings')->renewal_qualified) ? ($user->previousMonthRenew->sum('credit_used')) : ($user->currentMonthRenew->sum('credit_used')) >= app('settings')->renewal_qualified) ? '<i class="fa fa-fw fa-check-circle" style="color: #1e8011; text-align: center;"></i>' : '<i class="fa fa-fw fa-times-circle" style="color: #80100c; text-align: center;"></i>';
            })
            ->rawColumns(['group', 'status'])
            ->make(true);
    }

//    public function renew_summary_list_range(Request $request)
//    {
//        $query = UserCreditLog::with('user', 'user_related')
//            ->selectRaw('user_credit_logs.user_id, user_credit_logs.user_id_related, SUM(user_credit_logs.credit_used) as credit, MAX(user_credit_logs.created_at) as created_at')
//            ->whereHas('user', function ($query) {
//                $query->whereIn('group_id', [2,3,4]);
//            })
//            ->where(function ($query) {
//                $query->whereBetween('user_credit_logs.created_at', ['2018-04-01 00:00:00', '2018-04-07 15:00:00']);
//            })
//            ->where('direction', 'IN')
//            ->whereIn('type', ['TRANSFER-01', 'TRANSFER-02'])
//            ->groupBy(['user_id']);
//
//        return datatables()->eloquent($query)
//            ->addColumn('user', function (UserCreditLog $UserCreditLog) {
//                return (!auth()->user()->isAdmin() && $UserCreditLog->user->isAdmin()) ? 'Hidden' : $UserCreditLog->user->username;
//            })
//            ->addColumn('group', function (UserCreditLog $UserCreditLog) {
//                return '<span class="label label-' . $UserCreditLog->user->group->class . '">' . $UserCreditLog->user->group->name . '</span>';
//            })
//            ->addColumn('user_related', function (UserCreditLog $UserCreditLog) {
//                return (!auth()->user()->isAdmin() && $UserCreditLog->user_related->isAdmin()) ? 'Hidden' : $UserCreditLog->user_related->username;
//            })
//            ->addColumn('datetime_last_renewed', function (UserCreditLog $UserCreditLog) {
//                return $UserCreditLog->created_at->diffForHumans();
//            })
//            ->blacklist(['group', 'datetime_last_renewed'])
//            ->rawColumns(['group', 'renewal_qualified'])
//            ->make(true);
//    }
}
