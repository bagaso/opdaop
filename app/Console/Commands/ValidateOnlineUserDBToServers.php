<?php

namespace App\Console\Commands;

use App\Jobs\ValidateOnlineUserDBToServerJob;
use App\Server;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ValidateOnlineUserDBToServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:online-userdb-to-servers';

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
                $workers = ['validate_user-1', 'validate_user-2', 'validate_user-3'];
                $servers = Server::ServerOpenvpn()->get();
                foreach ($servers as $server) {
                    $job = (new ValidateOnlineUserDBToServerJob($server->id))->onConnection(app('settings')->queue_driver)->onQueue($workers[$ctr]);
                    dispatch($job);
                    dispatch($job)->delay(now()->addSeconds(30));
                    $ctr++;
                    if($ctr==3) $ctr=0;
                }
            }
        } catch (Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }
}
