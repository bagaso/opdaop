<?php

namespace App\Http\Controllers\Logs;

use App\AdminCreditLog;
use App\Http\Requests\Logs\SearchCreditLogRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FullCreditLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.full_credit_logs']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.logs.credit_logs');
    }

    public function log_list(SearchCreditLogRequest $request)
    {
        $query = AdminCreditLog::with('user_from', 'user_to')->selectRaw('admin_credit_logs.id, admin_credit_logs.user_id_from, admin_credit_logs.user_id_to, admin_credit_logs.type, admin_credit_logs.credit_used, admin_credit_logs.credit_before_from, admin_credit_logs.credit_after_from, admin_credit_logs.credit_before_to, admin_credit_logs.credit_after_to, admin_credit_logs.duration, admin_credit_logs.created_at');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="log_id" value="{{ $id }}">')
            ->addColumn('user_from', function (AdminCreditLog $log) {
                return (!auth()->user()->isAdmin() && $log->user_from->isAdmin()) ? '<span class="label label-' . $log->user_from->group->class . '">' . $log->user_from->group->name . '</span>' : $log->user_from->username;
            })
            ->addColumn('user_to', function (AdminCreditLog $log) {
                return $log->user_to->username;
            })
            ->filterColumn('user_from', function ($query, $keyword) {
                $query->where('group_id', '<>', 1);
            })
            ->rawColumns(['check', 'user_from', 'user_to'])
            ->make(true);
    }
}
