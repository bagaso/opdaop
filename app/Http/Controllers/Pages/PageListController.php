<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\Pages\DeletePageRequest;
use App\Http\Requests\Pages\SearchPageRequest;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageListController extends Controller
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
        return view('theme.default.pages.page_list');
    }

    public function page_list(SearchPageRequest $request)
    {
        $query = Page::CustomPage()->selectRaw('pages.id, pages.name, pages.slug_url, pages.is_public');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="page_id" value="{{ $id }}">')
            ->addColumn('link', function (Page $page) {
                return request()->getBaseUrl() . '/page/' . $page->slug_url;
            })
            ->editColumn('name', function (Page $page) {
                return '<a href="' . route('pages.edit', $page->id) . '">' . $page->name . '</a>';
            })
            ->rawColumns(['check', 'name'])
            ->make(true);
    }

    public function delete(DeletePageRequest $request)
    {
        Page::whereIn('id', $request->page_ids)->delete();
        return redirect()->back()->with('success_delete', 'Selected Page Deleted.');
    }
}
