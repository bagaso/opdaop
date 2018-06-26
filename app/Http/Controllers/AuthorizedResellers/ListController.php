<?php

namespace App\Http\Controllers\AuthorizedResellers;

use App\Http\Requests\AuthorizedResellers\RemoveResellerRequest;
use App\Http\Requests\AuthorizedResellers\SearchResellerRequest;
use App\User;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['access_page.authorized_reseller']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.authorized_resellers.reseller_list');
    }

    public function reseller_list(SearchResellerRequest $request)
    {
        $query = User::Distributors()->with('group')->selectRaw('users.id, users.fullname, users.contact, users.group_id, users.credits');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="user_id" value="{{ $id }}">')
            ->addColumn('group', function (User $user) {
                return '<span class="label label-' . $user->group->class . '">' . $user->group->name . '</span>';
            })
            ->rawColumns(['check', 'group'])
            ->make(true);
    }

    public function remove(RemoveResellerRequest $request)
    {
        User::whereIn('id', $request->user_ids)->update(['distributor' => 0]);
        return redirect()->back();
    }
}
