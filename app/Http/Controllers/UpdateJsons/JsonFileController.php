<?php

namespace App\Http\Controllers\UpdateJsons;

use App\UpdateJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JsonFileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($json = '')
    {
        if($json == '') {
            return redirect(route('index'));
        }
        $json_content = UpdateJson::where('slug_url', $json)->firstorfail();
        if(!$json_content->is_enable) {
            return response('Error.', 404);
        }
        $data = json_decode($json_content->json_data, true);
        $data['Version'] = $json_content->version;
        return $data;
    }
}
