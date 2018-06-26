<?php

namespace App\Http\Controllers\ManageServers;

use App\Http\Requests\ManageServers\ServerAddRequest;
use App\Server;
use App\ServerAccess;
use App\Subscription;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerAddController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_servers']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $server_accesses = ServerAccess::where('is_enable', 1)->get();
        $subscriptions = Subscription::where('is_enable', 1)->get();
        return view('theme.default.manage_servers.server_add', compact('server_accesses', 'subscriptions'));
    }

    public function add(ServerAddRequest $request)
    {
        try {
            $client = new Client(['base_uri' => 'https://api.cloudflare.com']);
            $response = $client->request('POST', '/client/v4/zones/' . app('settings')->cf_zone . '/dns_records',
                ['http_errors' => false, 'headers' => ['X-Auth-Email' => 'mp3sniff@gmail.com', 'X-Auth-Key' => 'ff245b46bd71002891e2890059b122e80b834', 'Content-Type' => 'application/json'], 'json' => ['type' => 'A', 'name' => $request->sub_domain, 'content' => $request->server_ip]]);

            $cloudflare = json_decode($response->getBody());

            if(!$cloudflare->success) {
                return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $cloudflare->errors[0]->message)->withInput();
            }
        } catch (\Exception $e) {
            return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $e->getMessage())->withInput();
        }


        $server = New Server();
        $server->cf_id = $cloudflare->result->id;
        $server->server_type = strtoupper($request->server_type);
        $server->server_name = $request->server_name;
        $server->server_ip = $request->server_ip;
        $server->sub_domain = strtoupper($request->sub_domain);
        $server->server_key = $request->server_key;
        $server->manager_password = $request->manager_password;
        $server->manager_port = $request->manager_port;
        $server->web_port = $request->web_port;
        $server->dl_speed = $request->download_speed;
        $server->up_speed = $request->upload_speed;
        $server->limit_bandwidth = $request->data_limit;
        $server->is_active = $request->status;
        $server->server_access_id = $request->server_access;
        $server->save();
        $server->subscriptions()->sync($request->subscription);
        return redirect()->back()->with('success', 'New Server Added.');
    }
}
