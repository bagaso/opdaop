<?php

namespace App\Jobs;

use App\HistoryVpn;
use App\Server;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ValidateOnlineUserDBToServerJob implements ShouldQueue
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
                    $online_users = $server->online_users;
                    $logs = $this->parseLog($server->server_ip, 'tcp', $server->web_port);

                    foreach($online_users as $online_user)
                    {
                        try {
                            $user = User::findorfail($online_user->user_id);
                            if(!in_array($user->username, array_pluck($logs, 'CommonName'))) {
                                $vpn_session = $user->vpn()->where('server_id', $server->id)->firstorfail();

                                $vpn_history = new HistoryVpn;
                                $vpn_history->user_id = $vpn_session->user_id;
                                $vpn_history->protocol = $vpn_session->protocol;
                                $vpn_history->user_ip = $vpn_session->user_ip;
                                $vpn_history->user_port = $vpn_session->user_port;
                                $vpn_history->server_name = $server->server_name;
                                $vpn_history->server_ip = $server->server_ip;
                                $vpn_history->sub_domain = $server->sub_domain;
                                $vpn_history->byte_sent = floatval($vpn_session->byte_sent);
                                $vpn_history->byte_received = floatval($vpn_session->byte_received);
                                $vpn_history->session_start = Carbon::parse($vpn_session->getOriginal('created_at'));
                                $vpn_history->session_end = Carbon::now();
                                $vpn_history->save();

                                $vpn_session->delete();
                            }

                        } catch (ModelNotFoundException $ex) {
                            #$job = (new OpenvpnDisconnectUserJob($log['CommonName'], $server->server_ip, $server->manager_port))->onConnection(app('settings')->queue_driver)->onQueue('disconnect_user');
                            #dispatch($job);
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

    public function availableIp($host, $port, $timeout=3) {
        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if($fp) {
            return true;
        } else {
            return false;
        }
    }


    public function parseLog($ip, $proto, $port=80) {
        $status = array();
        $ctr = 0;
        $uid = 0;

        if($this->availableIp($ip, $port)) {
            $handle = @fopen('http://' . $ip . ':' . $port . '/logs/logs.log', "r");

            if($handle) {
                while (!@feof($handle)) {
                    $buffer = @fgets($handle, 4096);

                    unset($match);

                    //if (ereg("^Updated,(.+)", $buffer, $match)) {
                    //$status['updated'] = $match[1];
                    //}

                    if (preg_match("/^(.+),(\d+\.\d+\.\d+\.\d+\:\d+),(\d+),(\d+),(.+)$/", $buffer, $match)) {
                        if ($match[1] <> 'Common Name' && $match[1] <> 'UNDEF' && $match[1] <> 'client') {
                            //      $cn = $match[1];

                            // for each remote ip:port because smarty doesnt
                            // like looping on strings in a section
                            $userlookup[$match[2]] = $uid;

                            $status[$ctr]['CommonName'] = $match[1];
                            $status[$ctr]['RealAddress'] = $match[2];
                            $status[$ctr]['BytesReceived'] = $match[3]; #sizeformat($match[3]);
                            $status[$ctr]['BytesSent'] = $match[4]; #sizeformat($match[4]);
                            $status[$ctr]['Since'] = $match[5];
                            $status[$ctr]['Proto'] = $proto;
                            $uid++; $ctr++;
                        }
                    }

                }
                @fclose($handle);
            }
        }

        return $status;
    }
}
