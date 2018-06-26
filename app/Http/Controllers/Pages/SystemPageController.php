<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\Pages\SystemPageUpdateRequest;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemPageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.pages']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = Page::findorfail(1);
        return view('theme.default.pages.system_page', compact('page'));
    }

    public function update(SystemPageUpdateRequest $request)
    {
        $page = Page::findorfail(1);
        $page->content = $request->html_content;
        $page->save();
        return redirect()->back()->with('success', 'System Page Updated.');
    }
}
