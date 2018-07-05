<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OpenvpnDisconnectUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $username, $server, $port;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($username, $server, $port)
    {
        $this->username = $username;
        $this->server = $server;
        $this->port = $port;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $socket = @fsockopen($this->server, $this->port, $errno, $errstr);
//        if($socket)
//        {
//            //echo "Connected";
//            //fputs($socket, "password\n");
//            @fputs($socket, "kill {$this->username}\n");
//            @fputs($socket, "quit\n");
//        }
//        @fclose($socket);
    }
}
