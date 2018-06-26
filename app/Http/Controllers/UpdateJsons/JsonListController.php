<?php

namespace App\Http\Controllers\UpdateJsons;

use App\Http\Requests\UpdateJsons\DeleteJsonRequest;
use App\Http\Requests\UpdateJsons\SearchJsonRequest;
use App\UpdateJson;
use App\Http\Controllers\Controller;

class JsonListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.update_jsons']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.update_jsons.json_list');
    }

    public function json_list(SearchJsonRequest $request)
    {
        $query = UpdateJson::selectRaw('update_jsons.*');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="json_id" value="{{ $id }}">')
            ->addColumn('link', function (UpdateJson $json) {
                return request()->getBaseUrl() . '/json/' . $json->slug_url;
            })
            ->editColumn('name', function (UpdateJson $json) {
                return '<a href="' . route('json.edit', $json->id) . '">' . $json->name . '</a>';
            })
            ->rawColumns(['check', 'name'])
            ->make(true);
    }

    public function delete(DeleteJsonRequest $request)
    {
        UpdateJson::whereIn('id', $request->json_ids)->delete();
        return redirect()->back()->with('success_delete', 'Selected Json File Deleted.');
    }
}
