<?php

namespace App\Console\Commands;

use App\UserActionLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActionLogsClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:action-logs-clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old action logs record.';

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
        $duration_type = substr(app('settings')->action_logs_lifespan, -1, 1);
        $duration_interval = (int) filter_var(app('settings')->action_logs_lifespan, FILTER_SANITIZE_NUMBER_INT);
        $life_span = (($duration_type == 'd') ? $duration_interval * (3660 * 24) : 0) + (($duration_type == 'm') ? $duration_interval * (3600 * 24 * 30) : 0) + (($duration_type == 'y') ? $duration_interval * (3600 * 24 * 30 * 12) : 0);
        UserActionLog::where('created_at', '<=', Carbon::now()->subSeconds($life_span))->delete();
    }
}
