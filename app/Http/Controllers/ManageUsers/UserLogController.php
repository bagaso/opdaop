<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserLogRequest;
use App\User;
use App\UserActionLog;
use App\UserCreditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_logs']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        return view('theme.default.manage_users.user_log', compact('user'));
    }

    public function log_action(UserLogRequest $request, $id = 0)
    {
        $user = User::findorfail($id);
        $query = UserActionLog::with('user_related')
            ->selectRaw('user_action_logs.id, user_action_logs.user_id, user_action_logs.user_id_related, user_action_logs.action, user_action_logs.from_ip, user_action_logs.created_at')
            ->where('user_id', $user->id);

        return datatables()->eloquent($query)
            ->addColumn('user_related', function (UserActionLog $UserActionLog) {
                return (!auth()->user()->isAdmin() && $UserActionLog->user_related->isAdmin()) ? '<span class="label label-' . $UserActionLog->user_related->group->class . '">' . $UserActionLog->user_related->group->name . '</span>' : $UserActionLog->user_related->username;
            })
            ->rawColumns(['user_related'])
            ->make(true);
    }

    public function log_credit(UserLogRequest $request, $id = 0)
    {
        $user = User::findorfail($id);
        $query = UserCreditLog::with('user_related')->selectRaw('user_credit_logs.id, user_credit_logs.user_id, user_credit_logs.user_id_related, user_credit_logs.type, user_credit_logs.direction, user_credit_logs.credit_used, user_credit_logs.duration, user_credit_logs.credit_before, user_credit_logs.credit_after, user_credit_logs.created_at')->where('user_id', $user->id);
        return datatables()->eloquent($query)
            ->addColumn('user_related', function (UserCreditLog $UserCreditLog) {
                return (!auth()->user()->isAdmin() && $UserCreditLog->user_related->isAdmin()) ? '<span class="label label-' . $UserCreditLog->user_related->group->class . '">' . $UserCreditLog->user_related->group->name . '</span>' : $UserCreditLog->user_related->username;
            })
            ->rawColumns(['user_related'])
            ->make(true);
    }
}
