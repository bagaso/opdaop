<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\ProfileRequest;
use App\Http\Requests\Account\RemovePhotoRequest;
use App\Http\Requests\Account\UploadPhotoRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
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
        return view('theme.default.account.profile');
    }

    public function update(ProfileRequest $request)
    {
        $account = User::findorfail(auth()->user()->id);
        $date_now = Carbon::now();
        $account->username = $request->username ? strtolower($request->username) : auth()->user()->username;
        $account->email = $request->email;
        $account->fullname = $request->fullname;
        $account->contact = $request->contact;
        $account->distributor = !auth()->user()->isAdmin() ? (in_array(auth()->user()->group_id, [1,2,3,4]) ? ($request->distributor == 'on' ?  1 : 0) : 0) : 0;
        $account->save();
        if($account->wasChanged()) {
            DB::table('user_action_logs')->insert([
                [
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $account->id,
                    'user_id_related' => $account->id,
                    'action' => 'You have Updated your Account.',
                    'from_ip' => Request::getClientIp(),
                    'created_at' => $date_now,
                    'updated_at' => $date_now,
                ]
            ]);
        }
        return redirect()->back()->with(['success' => 'Account Updated.', 'set' => 0]);
    }

    public function upload(UploadPhotoRequest $request)
    {

        $img = Image::make($request->file('file'));
        $photo = (string)base64_encode($img->resize(250,250)->encode('jpg', 50)->destroy());
        $account = User::findorfail(auth()->user()->id);
        $account->photo = $photo;
        $account->save();
        return redirect()->back()->with(['success' => 'Photo Updated.', 'set' => 1]);
    }

    public function remove_photo(RemovePhotoRequest $request)
    {
        $account = User::findorfail(auth()->user()->id);
        $account->photo = null;
        $account->save();
        return redirect()->back()->with(['success' => 'Photo Removed.', 'set' => 2]);
    }
}
