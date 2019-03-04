<?php

namespace App\Console\Commands;

use App\OnlineUser;
use App\User;
use Illuminate\Console\Command;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteIdleUserOpenvpn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete_idle:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::connection()->getPdo();
            if(Schema::hasTable('settings')) {
                $delete_idle = OnlineUser::with(['user', 'server'])->where('updated_at', '<=', \Carbon\Carbon::now()->subMinutes(3));

                foreach ($delete_idle->get() as $online_user) {
                    try {
                        $user = User::findorfail($online_user->user_id);
                        $job = (new JobVpnDisconnectUser($user->username, $online_user->server->server_ip, $online_user->server->server_port))->onConnection(app('settings')->queue_driver)->onQueue('disconnect_user');
                        dispatch($job);
                        $user->timestamps = false;
                        if(!$user->isAdmin() && $online_user->server->limit_bandwidth && $online_user->data_available > 0) {
                            $data = doubleval($online_user->data_available) - doubleval($online_user->byte_sent);
                            $user->consumable_data = ($data >= 0) ? $data : 0;
                            $user->save();
                        }
                        $user->lifetime_bandwidth = doubleval($user->lifetime_bandwidth) + doubleval($online_user->byte_sent);
                        $user->save();

                        $vpn_history = new HistoryVpn;
                        $vpn_history->user_id = $online_user->user_id;
                        $vpn_history->protocol = $online_user->protocol;
                        $vpn_history->user_ip = $online_user->user_ip;
                        $vpn_history->user_port = $online_user->user_port;
                        $vpn_history->server_name = $online_user->server->server_name;
                        $vpn_history->server_ip = $online_user->server->server_ip;
                        $vpn_history->sub_domain = $online_user->server->sub_domain;
                        $vpn_history->byte_sent = floatval($online_user->bytes_sent);
                        $vpn_history->byte_received = floatval($online_user->bytes_received);
                        $vpn_history->session_start = Carbon::parse($online_user->getOriginal('created_at'));
                        $vpn_history->session_end = Carbon::now();
                        $vpn_history->save();
                    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
                        //
                    }
                }
                $delete_idle->delete();
            }
        } catch (Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }
}
