<?php

namespace App\Http\Controllers\ManageServers;

use App\Http\Requests\ManageServers\ServerDeleteRequest;
use App\Http\Requests\ManageServers\ServerListSearchRequest;
use App\Server;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerListController extends Controller
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
        return view('theme.default.manage_servers.server_list');
    }

    public function server_list(ServerListSearchRequest $request)
    {
        $query = Server::with('server_access', 'subscriptions', 'online_users')->selectRaw('servers.id, servers.server_access_id, servers.server_name, servers.server_ip, servers.sub_domain, servers.is_active');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="server_id" value="{{ $id }}">')
            ->addColumn('server_access', function (Server $server) {
                return '<span class="label label-' . $server->server_access->class . '">' . $server->server_access->name . '</span>';
            })
            ->editColumn('server_name', function (Server $server) {
                return '<a href="' . route('manage_servers.server_edit', $server->id) . '">' . $server->server_name . '</a>';
            })
            ->editColumn('subscriptions', function (Server $server) {
                $sub = '';
                foreach ($server->subscriptions as $subscription) {
                    $sub .= '<span class="label label-' . $subscription->class . '">' . $subscription->name . '</span> ';
                }
                return $sub;
            })
            ->editColumn('online_users', function (Server $server) {
                return $server->online_users->count();
            })
            ->editColumn('is_active', function (Server $server) {
                return $server->is_active ? '<i class="fa fa-fw fa-check-circle" style="color: #1e8011; text-align: center;"></i>' : '<i class="fa fa-fw fa-times-circle" style="color: #80100c; text-align: center;"></i>';
            })
            ->blacklist(['check', 'online_users'])
            ->rawColumns(['check', 'server_name', 'server_access', 'subscriptions', 'is_active'])
            ->make(true);
    }

    public function delete(ServerDeleteRequest $request)
    {
        foreach ($request->server_ids as $id) {
            $server = Server::findorfail($id);
            if ($server->online_users->count() > 0) {
                return  redirect()->back()->with('delete_error_online_users', 'Server cannot be deleted while users are logged in.');
            }
        }

        try {
            foreach ($request->server_ids as $id) {

                $server = Server::find($id);

                $client = new Client(['base_uri' => 'https://api.cloudflare.com']);
                $response = $client->request('DELETE', '/client/v4/zones/' . app('settings')->cf_zone . '/dns_records/' . $server->cf_id,
                    ['http_errors' => false, 'headers' => ['X-Auth-Email' => 'mp3sniff@gmail.com', 'X-Auth-Key' => 'ff245b46bd71002891e2890059b122e80b834', 'Content-Type' => 'application/json']]);

                $cloudflare = json_decode($response->getBody());

                if(!$cloudflare->success) {
                    return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $cloudflare->errors[0]->message);
                }
                $server->server_access()->detach();
                $server->delete();
            }
        } catch (\Exception $e) {
            return  redirect()->back()->with('error_cloudflare', 'Cloudflare: ' . $e->getMessage());
        }

        return redirect()->back()->with('success_delete', 'Selected Server Deleted.');
    }
}
