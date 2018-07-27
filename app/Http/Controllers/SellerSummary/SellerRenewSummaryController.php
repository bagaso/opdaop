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
        $query = User::with('group', 'upline', 'previousMonthRenew', 'previousMonthRenew.user_related', 'latestRenew')
            ->selectRaw('users.id, users.username, users.group_id, users.parent_id, users.seller_first_applied_credit')
            ->whereIn('group_id', [2,3,4])
            ->where('status_id', '=', 2)
            ->orderBy('username');
        return datatables()->eloquent($query)
            ->addColumn('group', function (User $user) {
                return '<span class="label label-' . $user->group->class . '">' . $user->group->name . '</span>';
            })
            ->addColumn('upline', function (User $user) {
                return (!auth()->user()->isAdmin() && $user->upline->isAdmin()) ? '<span class="label label-' . $user->upline->group->class . '">' . $user->upline->group->name . '</span>' : $user->upline->username;
            })
            ->addColumn('credit_accumulated', function (User $user) {
                return $user->accumulatedCredit;
            })
            ->addColumn('latest_renew', function (User $user) {
                return $user->latestRenew->created_at;
            })
            ->addColumn('status', function (User $user) {
                return $user->seller_status ? '<i class="fa fa-fw fa-check-circle" style="color: #1e8011; text-align: center;"></i>' : '<i class="fa fa-fw fa-times-circle" style="color: #80100c; text-align: center;"></i>';
            })
            ->rawColumns(['group', 'status'])
            ->make(true);
    }
}
