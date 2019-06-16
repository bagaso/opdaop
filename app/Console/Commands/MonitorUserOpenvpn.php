<?php

namespace App\Console\Commands;

use App\Jobs\MonitorUserOpenvpnJob;
use App\Server;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MonitorUserOpenvpn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor_user:openvpn';

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
                $ctr = 0;
                $workers = ['monitor_user-1', 'monitor_user-2', 'monitor_user-3', 'monitor_user-4', 'monitor_user-5', 'monitor_user-6'];
                $servers = Server::ServerOpenvpn()->get();
                foreach ($servers as $server) {
                    $job = (new MonitorUserOpenvpnJob($server->id))->onConnection(app('settings')->queue_driver)->onQueue($workers[$ctr]);
                    dispatch($job);
                    dispatch($job)->delay(now()->addSeconds(35));
                    $ctr++;
                    if($ctr==6) $ctr=0;
                }
            }
        } catch (Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }
}
