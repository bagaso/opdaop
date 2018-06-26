<?php

namespace App\Http\Controllers\Account;

use App\UserActionLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionLogController extends Controller
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
        return view('theme.default.account.action_log');
    }

    public function action_log_list()
    {
        $query = UserActionLog::with('user_related')
            ->selectRaw('user_action_logs.id, user_action_logs.user_id, user_action_logs.user_id_related, user_action_logs.action, user_action_logs.from_ip, user_action_logs.created_at')
            ->where('user_id', auth()->user()->id);

        return datatables()->eloquent($query)
            ->addColumn('user_related', function (UserActionLog $UserActionLog) {
                if(auth()->user()->id == $UserActionLog->user_related->id) {
                    return '-';
                }
                return (!auth()->user()->isAdmin() && $UserActionLog->user_related->isAdmin()) ? '<span class="label label-' . $UserActionLog->user_related->group->class . '">' . $UserActionLog->user_related->group->name . '</span>' : $UserActionLog->user_related->username;
            })
            ->rawColumns(['user_related'])
            ->make(true);
    }
}
