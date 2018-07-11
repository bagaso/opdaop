<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\AccountLogSearchRequest;
use App\UserCreditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CreditLogController extends Controller
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
        return view('theme.default.account.credit_log');
    }

    public function credit_log_list(AccountLogSearchRequest $request)
    {
        $query = UserCreditLog::with('user_related')
            ->selectRaw('user_credit_logs.id, user_credit_logs.user_id, user_credit_logs.user_id_related, user_credit_logs.type, user_credit_logs.direction, user_credit_logs.credit_used, user_credit_logs.duration, user_credit_logs.credit_before, user_credit_logs.credit_after, user_credit_logs.created_at')
            ->where('user_id', auth()->user()->id);

        return datatables()->eloquent($query)
            ->addColumn('user_related', function (UserCreditLog $UserCreditLog) {
                return (!auth()->user()->isAdmin() && $UserCreditLog->user_related->isAdmin()) ? '<span class="label label-' . $UserCreditLog->user_related->group->class . '">' . $UserCreditLog->user_related->group->name . '</span>' : $UserCreditLog->user_related->username;
            })
            ->rawColumns(['user_related'])
            ->make(true);
    }
}
