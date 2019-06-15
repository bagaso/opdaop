<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HistoryVpnClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history_vpns:delete-old-records';

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
        $duration_type = substr(app('settings')->vpn_history_lifespan, -1, 1);
        $duration_interval = (int) filter_var(app('settings')->vpn_history_lifespan, FILTER_SANITIZE_NUMBER_INT);
        $life_span = (($duration_type == 'd') ? $duration_interval * (3660 * 24) : 0) + (($duration_type == 'm') ? $duration_interval * (3600 * 24 * 30) : 0) + (($duration_type == 'y') ? $duration_interval * (3600 * 24 * 30 * 12) : 0);
        DB::table('history_vpns')->where('session_end', '<=', Carbon::now()->subSeconds($life_span))->delete();
    }
}
