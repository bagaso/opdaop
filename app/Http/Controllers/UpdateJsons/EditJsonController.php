<?php

namespace App\Http\Controllers\UpdateJsons;

use App\Http\Requests\UpdateJsons\UpdateJsonRequest;
use App\UpdateJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditJsonController extends Controller
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
    public function index($id = 0)
    {
        $json = UpdateJson::findorfail($id);
        return view('theme.default.update_jsons.update_json', compact('json'));
    }

    public function update(UpdateJsonRequest $request, $id = 0)
    {
        $json = UpdateJson::findorfail($id);
        $json->name = $request->json_name;
        $json->json_data = $request->json_data;
        $json->version = $request->version;
        $json->slug_url = str_slug($request->json_name, '-');
        $json->is_enable = $request->is_enable == 'on' ?  1 : 0;
        $json->save();
        return redirect()->back()->with('success', 'Json Updated.');
    }
}
