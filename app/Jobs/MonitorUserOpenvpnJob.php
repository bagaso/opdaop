<?php

namespace App\Jobs;

use App\Server;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MonitorUserOpenvpnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $server_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($server_id)
    {
        $this->server_id = $server_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::connection()->getPdo();
            if(Schema::hasTable('settings')) {
                try {

                    $server = Server::findorfail($this->server_id);
                    $users = $server->online_user();
                    foreach($users as $user)
                    {
                        try {
                            $user = User::where('username', $log['CommonName'])->firstorfail();
                            $real_address = explode(":", $log['RealAddress']);
                            $vpn_session = $user->vpn()->where('server_id', $this->server_id)->firstorfail();
                            $vpn_session->byte_sent = floatval($log['BytesSent']) ? floatval($log['BytesSent']) : 0;
                            $vpn_session->byte_received = floatval($log['BytesReceived']) ? floatval($log['BytesReceived']) : 0;
                            $vpn_session->save();
                        } catch (ModelNotFoundException $ex) {
                            $job = (new OpenvpnDisconnectUserJob($log['CommonName'], $server->server_ip, $server->manager_port))->onConnection(app('settings')->queue_driver)->onQueue('disconnect_user');
                            dispatch($job);
                        }
                    }

                } catch (ModelNotFoundException $ex) {
                    //die("Could not connect to the database.  Please check your configuration.");
                }
            }
        } catch (Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }
}
