<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\Pages\AddPageRequest;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddPageController extends Controller
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
        return view('theme.default.pages.page_add');
    }

    public function create(AddPageRequest $request)
    {
        $page = new Page();
        $page->name = $request->page_name;
        $page->content = $request->html_content;
        $page->slug_url = str_slug($request->page_name, '-');
        $page->is_public = $request->public == 'on' ?  1 : 0;;
        $page->save();
        return redirect()->back()->with('success', 'New Page Created.');
    }
}
