<?php

namespace App\Console\Commands;

use App\Jobs\UpdateLogOpenvpnJob;
use App\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UpdateLogOpenvpn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_log:openvpn';

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
                $worker = array('log_update-1', 'log_update-2', 'log_update-3');
                $servers = Server::Active()->get();
                foreach ($servers as $server) {
                    Log::info('wew2');
                    $job = (new UpdateLogOpenvpnJob($server->id))->onConnection(app('settings')->queue_driver)->onQueue($worker[$ctr]);
                    dispatch($job);
                    $ctr++;
                    if($ctr==3) $ctr=0;
                }
                Log::info('wew1');
            }
        } catch (\Exception $e) {
            Log::info('wew');
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }
}
