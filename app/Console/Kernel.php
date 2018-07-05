<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    public function bootstrap()
    {
        // Don't forget to call parent bootstrap
        parent::bootstrap();

        // Do your own bootstrapping stuff here

    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        try {
            DB::connection()->getPdo();
            if(Schema::hasTable('settings')) {
                $schedule->command('update_log:openvpn')->everyMinute();
                if(app('settings')->enable_backup) {
                    $schedule->command('backup:run --only-db')->cron(app('settings')->backup_cron);
                }
                $schedule->command('backup:delete-old-file')->hourly();
                $schedule->command('logs:action-logs-clean')->hourly();
                $schedule->command('failed_jobs:truncate')->daily();
            }
        } catch (\Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
