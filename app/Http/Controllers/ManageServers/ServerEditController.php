<?php

namespace App\Http\Controllers\ManageServers;

use App\Http\Requests\ManageServers\AddPrivateUserRequest;
use App\Http\Requests\ManageServers\ServerEditRequest;
use App\Server;
use App\ServerAccess;
use App\Subscription;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerEditController extends Controller
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
    public function index($id = 0)
    {
        $server = Server::findorfail($id);
        $server_accesses = ServerAccess::where('is_enable', 1)->get();
        $subscriptions = Subscription::where('is_enable', 1)->get();
        return view('theme.default.manage_servers.server_edit', compact('server','server_accesses', 'subscriptions'));
    }

    public function update(ServerEditRequest $request, $id = 0)
    {
        $server = Server::findorfail($id);

        try {
            $client = new Client(['base_uri' => 'https://api.cloudflare.com']);
            $response = $client->request('PUT', '/client/v4/zones/' . app('settings')->cf_zone . '/dns_records/' . $server->cf_id,
                ['http_errors' => false, 'headers' => ['X-Auth-Email' => 'mp3sniff@gmail.com', 'X-Auth-Key' => 'ff245b46bd71002891e2890059b122e80b834', 'Content-Type' => 'application/json'], 'json' => ['type' => 'A', 'name' => $request->sub_domain, 'content' => $request->server_ip]]);

            $cloudflare = json_decode($response->getBody());

            if(!$cloudflare->success) {
                return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $cloudflare->errors[0]->message)->withInput();
            }
        } catch (\Exception $e) {
            return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $e->getMessage())->withInput();
        }

        Server::where('id', $server->id)->update(
            [
                'server_type' => $request->server_type,
                'server_name' => strtoupper($request->server_name),
                'server_ip' => $request->server_ip,
                'sub_domain' => strtoupper($request->sub_domain),
                'server_key' => $request->server_key,
                'manager_password' => $request->manager_password,
                'manager_port' => $request->manager_port,
                'web_port' => $request->web_port,
                'dl_speed_openvpn' => $request->download_speed,
                'up_speed_openvpn' => $request->upload_speed,
                'limit_bandwidth' => $request->data_limit,
                'is_active' => $request->status,
                'server_access_id' => $request->server_access,
            ]
        );
        $server->subscriptions()->sync($request->subscription);
        return redirect()->back()->with(['success' => 'Server Updated.', 'set' => 0]);
    }

    public function add_user(AddPrivateUserRequest $request, $id = 0)
    {
        $server = Server::findorfail($id);
        $user = User::where('username', $request->username)->get();

        $server->privateUsers()->attach($user->id);
        return redirect()->back()->with(['success' => 'User Added.', 'set' => 1]);
    }
}
