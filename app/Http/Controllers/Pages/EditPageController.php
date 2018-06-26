<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\Pages\EditPageRequest;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditPageController extends Controller
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
    public function index($id = 0)
    {
        $page = Page::findorfail($id);
        return view('theme.default.pages.page_edit', compact('page'));
    }

    public function update(EditPageRequest $request, $id)
    {
        $page = Page::findorfail($id);
        $page->name = $request->page_name;
        $page->content = $request->html_content;
        $page->slug_url = str_slug($request->page_name, '-');
        $page->is_public = $request->public == 'on' ?  1 : 0;
        $page->save();
        return redirect()->back()->with('success', 'Page Updated.');
    }
}
