<?php

namespace App\Http\Controllers\ManageUsers;

use App\Http\Requests\ManageUsers\UserPermissionRequest;
use App\Permission;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class UserPermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_permission']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        $permissions = Permission::where('group_id', $user->group_id)
            ->where(function($query) {
                if(!auth()->user()->isAdmin()) {
                    $query->where('is_controllable', 1);
                }
            })->orderBy('id')->get();
        return view('theme.default.manage_users.user_permission', compact('user', 'permissions'));
    }

    public function update(UserPermissionRequest $request, $id = 0)
    {
        $user = User::findorfail($id);
        $date_now = Carbon::now();
        $selected_permission = array_map('intval', $request->permissions ? $request->permissions : []);
        $wasChanged = count(array_diff($selected_permission, json_decode($user->permissions->pluck('id')))) + count(array_diff(json_decode($user->permissions->pluck('id')), $selected_permission));
        if(auth()->user()->isAdmin()) {
            $user->permissions()->sync($request->permissions);
        } else {
            $user->permissions()->detach($user->permissions()->where([['is_controllable', 1], ['group_id', $user->group_id]])->pluck('id'));
            $user->permissions()->attach($request->permissions);
        }

        if($wasChanged > 0) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have Updated Permission of a User.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Permission was Updated.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with('success', 'User Permission Updated.');
    }
}
