<?php

namespace App\Http\Controllers\ManageUsers;

use App\Group;
use App\Http\Requests\ManageUsers\UserProfileRequest;
use App\Http\Requests\ManageUsers\UserRemovePhotoRequest;
use App\Http\Requests\ManageUsers\UserUploadPhotoRequest;
use App\Permission;
use App\Status;
use App\Subscription;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_users:user_profile']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $user = User::findorfail($id);
        $groups = Group::all();
        $statuses = Status::all();
        $subscriptions = Subscription::all();
        return view('theme.default.manage_users.user_profile', compact('user', 'groups', 'statuses', 'subscriptions'));
    }

    public function update(UserProfileRequest $request, $id = 0)
    {
        $user = User::findorfail($id);

        $old_group_id = $user->group->id;
        $date_now = Carbon::now();
        $expired_at = Carbon::parse($user->getOriginal('expired_at'));

        $user_subscription = Subscription::findorfail($request->subscription);
        $old_user_subscription = Subscription::findorfail($user->subscription_id);

        $user->group_id = $request->group ? $request->group : $user->group_id;
        $user->username = $request->username ? strtolower($request->username) : strtolower($user->username);
        $user->email = strtolower($request->email);
        $user->fullname = $request->fullname;
        $user->contact = $request->contact;
        $user->distributor = in_array($request->group, [2,3,4]) ? ($request->distributor == 'on' ?  1 : 0) : 0;
        $user->max_users = $request->max_users ? $request->max_users : $user->max_users;
        $user->status_id = $request->status;
        $user->subscription_id = $request->subscription ? $request->subscription : $user->subscription_id;
        $user->expired_at = $user->paidSubscription() ? ($old_user_subscription->id <> $user_subscription->id ? $date_now->copy()->addSeconds((($date_now->diffInSeconds($expired_at) * intval($old_user_subscription->cost)) / intval($user_subscription->cost))) : $expired_at) : $expired_at;
        $user->save();
        if($old_group_id <> ($request->group ? $request->group : $old_group_id)) {
            $permissions = Permission::where([['group_id', ($request->group ? $request->group : $user->group_id)], ['is_default', 1]])->pluck('id');
            $user->permissions()->sync($permissions);
        }
        if($user->wasChanged()) {
            $date_now = Carbon::now();
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => auth()->user()->id,
                    'user_id_related' => $user->id,
                    'action' => 'You have updated user profile.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'user_id_related' => auth()->user()->id,
                    'action' => 'Your Account was updated.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'User Updated.', 'set' => 0]);
    }

    public function upload(UserUploadPhotoRequest $request, $id)
    {

        $img = Image::make($request->file('file'));
        $photo = (string)base64_encode($img->resize(250,250)->encode('jpg', 50)->destroy());
        $user = User::findorfail($id);
        $user->photo = $photo;
        $user->save();
        return redirect()->back()->with(['success' => 'User Photo Updated.', 'set' => 1]);
    }

    public function remove_photo(UserRemovePhotoRequest $request, $id)
    {
        $user = User::findorfail($id);
        $user->photo = null;
        $user->save();
        return redirect()->back()->with(['success' => 'User Photo Removed.', 'set' => 2]);
    }
}
