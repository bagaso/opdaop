<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\DeleteUserRequest;
use App\Http\Requests\ManageUsers\FreezeUserRequest;
use App\Http\Requests\ManageUsers\UserListAllSearchRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserListAllController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_list_all']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.manage_users.user_list_all');
    }

    public function user_list(UserListAllSearchRequest $request)
    {
        $query = User::UserAll(auth()->user())->with('group', 'subscription', 'status', 'upline')->selectRaw('users.id, users.username, users.email, users.group_id, users.subscription_id, users.status_id, users.freeze_mode, users.credits, users.expired_at, users.parent_id, users.created_at');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="user_id" value="{{ $id }}">')
            ->addColumn('group', function (User $user) {
                return '<span class="label label-' . $user->group->class . '">' . $user->group->name . '</span>';
            })
            ->addColumn('subscription', function (User $user) {
                return '<span class="label label-' . $user->subscription->class . '">' . $user->subscription->name . '</span>';
            })
            ->addColumn('status', function (User $user) {
                return '<span class="label label-' . $user->status->class . '">' . $user->status->name_get . '</span>';
            })
            ->addColumn('upline', function (User $user) {
                return $user->upline->username;
            })
            ->editColumn('username', function (User $user) {
                return '<a href="' . route('manage_users.user_profile', $user->id) . '">' . $user->username . '</a>';
            })
            ->editColumn('credits', function (User $user) {
                return '<span class="label label-' . $user->credits_class . '">' . $user->credits . '</span>';
            })
            ->editColumn('expired_at', function ($user) {
                return '<span class="label label-' . $user->expired_at_class . '">' . $user->expired_at . '</span>';
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? $user->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            })
            ->filterColumn('credits', function ($query, $keyword) {
                if(str_contains('no limit', strtolower($keyword))) {
                    $query->whereExists(function($query)
                    {
                        $query->select(DB::raw(1))
                            ->from('permission_user')
                            ->whereRaw('permission_user.user_id = users.id');
                    });
                } else {
                    $query->whereRaw("credits like ?", ["%$keyword%"]);
                }
            })
            ->filterColumn('expired_at', function ($query, $keyword) {
                if(str_contains('expired', strtolower($keyword))) {
                    $query->where('expired_at', '<=', Carbon::now());
                } else if(str_contains('freezed', strtolower($keyword))) {
                    $query->where('freeze_mode', 1);
                } else {
                    //
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d') like ?", ["%$keyword%"]);
            })
            ->filterColumn('upline', function ($query, $keyword) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('username like ?', ["%$keyword%"]);
            })
            ->rawColumns(['check', 'username', 'group', 'subscription', 'status', 'credits', 'expired_at'])
            ->make(true);
    }

    public function delete_user(DeleteUserRequest $request)
    {
        User::whereIn('id', $request->user_ids)->delete();
        return redirect()->back()->with('success', 'Selected User Deleted.');
    }

    public function freeze_user(FreezeUserRequest $request)
    {
        $date_now = Carbon::now();
        foreach ($request->user_ids as $user_id) {
            $user = User::findorfail($user_id);
            if(auth()->user()->can('UPDATE_USER_FREEZE_ID', $user->id)) {
                # Disallow if user is 'Expired'
                if($user->expired_at == 'Expired') {
                    return redirect()->back()->withErrors(['user_ids' => '1111']);
                }
                # Disallow if account has no permission to bypass USER_BYPASS_FREEZE_LIMIT and user is no freeze left
                if($user->freeze_ctr < 1 && auth()->user()->cannot('BYPASS_USER_FREEZE_LIMIT_ID', $user->id)) {
                    return redirect()->back()->withErrors(['user_ids' => '2222']);
                }
            }
            DB::transaction(function () use ($user, $date_now) {
                User::where('id', $user->id)->update([
                    'freeze_start' => $date_now,
                    'freeze_mode' => 1,
                    'freeze_ctr' => ($user->freeze_ctr < 1 && auth()->user()->can('BYPASS_USER_FREEZE_LIMIT_ID', $user->id)) ? $user->freeze_ctr : ($user->freeze_ctr - 1),
                ]);
                DB::table('user_action_logs')->insert([
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'user_id' => auth()->user()->id,
                        'user_id_related' => $user->id,
                        'action' => 'You have Enabled Freeze of a User.',
                        'from_ip' => Request::getClientIp(),
                        'created_at' => $date_now,
                        'updated_at' => $date_now,
                    ],
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'user_id' => $user->id,
                        'user_id_related' => auth()->user()->id,
                        'action' => 'Your Account Freeze was Enabled.',
                        'from_ip' => Request::getClientIp(),
                        'created_at' => $date_now,
                        'updated_at' => $date_now,
                    ]
                ]);
            });
        }
        #User::whereIn('id', $request->user_ids)->update(['freeze_start' => $date_now, 'freeze_mode' => 1]);
        return redirect()->back()->with('success', 'Selected User Freezed.');
    }
}
