<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:delete-old-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes old files.';

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
        $files = Storage::disk(app('settings')->backup_disks)->files(app('settings')->backup_directory);
        foreach ($files as $file) {
            $file_dt = Carbon::createFromTimestamp(Storage::disk(app('settings')->backup_disks)->lastModified($file));
            $dt = Carbon::now();
            if($file_dt->diffInDays($dt) == app('settings')->backup_lifespan) {
                Storage::disk(app('settings')->backup_disks)->delete($file);
            }
        }
    }
}
