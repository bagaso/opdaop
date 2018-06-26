<?php

namespace App\Http\Controllers\OnlineUsers;

use App\Http\Requests\OnlineUsers\DeleteOnlineUserRequest;
use App\Http\Requests\OnlineUsers\SearchOnlineUserRequest;
use App\OnlineUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OnlineListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.online_users.online_list');
    }

    public function online_list(SearchOnlineUserRequest $request)
    {
        $query = OnlineUser::with('user', 'server')->selectRaw('online_users.id, online_users.user_id, online_users.server_id, online_users.byte_sent, online_users.byte_received, online_users.created_at');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="ids" value="{{ $id }}">')
            ->addColumn('user', function (OnlineUser $online) {
                return $online->user->username;
            })
            ->addColumn('server', function (OnlineUser $online) {
                return $online->server->server_name;
            })
            ->rawColumns(['check', 'group'])
            ->make(true);
    }

    public function force_delete_user(DeleteOnlineUserRequest $request)
    {
        OnlineUser::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected User Deleted.');
    }
}
