<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        try {
            DB::connection()->getPdo();
            if(Schema::hasTable('settings')) {
                if(Setting::where('id', 1)->exists()) {
                    Config::set('app.name', app('settings')->site_name ? app('settings')->site_name : '');
                    Config::set('app.url', app('settings')->site_url ?  app('settings')->site_url : '');

                    Config::set('mail.driver', app('settings')->mail_driver ? app('settings')->mail_driver : '');
                    Config::set('mail.host', app('settings')->mail_host ? app('settings')->mail_host : '');
                    Config::set('mail.port', app('settings')->mail_port ? app('settings')->mail_port : '');
                    Config::set('mail.encryption', app('settings')->mail_encryption ? app('settings')->mail_encryption : '');
                    Config::set('services.sparkpost.secret', app('settings')->mail_secret_code ? app('settings')->mail_secret_code : '');
                    Config::set('mail.from.address', app('settings')->mail_from_address ? app('settings')->mail_from_address : '');
                    Config::set('mail.from.name', app('settings')->mail_from_name ? app('settings')->mail_from_name : '');

                    if(app('settings')->backup_disks == 'ftp') {
                        Config::set('filesystems.disks.ftp.host', app('settings')->backup_ftp_host ? app('settings')->backup_ftp_host : '');
                        Config::set('filesystems.disks.ftp.username', app('settings')->backup_ftp_username ? app('settings')->backup_ftp_username : '');
                        Config::set('filesystems.disks.ftp.password', app('settings')->backup_ftp_password ? app('settings')->backup_ftp_password : '');
                    }

                    if(app('settings')->backup_disks == 's3') {
                        Config::set('filesystems.disks.s3.key', app('settings')->backup_aws_key ? app('settings')->backup_aws_key : '');
                        Config::set('filesystems.disks.s3.secret', app('settings')->backup_aws_secret ? app('settings')->backup_aws_secret : '');
                        Config::set('filesystems.disks.s3.region', app('settings')->backup_aws_region ? app('settings')->backup_aws_region : '');
                        Config::set('filesystems.disks.s3.bucket', app('settings')->backup_aws_bucket ? app('settings')->backup_aws_bucket : '');
                    }

                    if(app('settings')->backup_disks == 'rackspace') {
                        Config::set('filesystems.disks.rackspace.username', app('settings')->backup_rackspace_username ? app('settings')->backup_rackspace_username : '');
                        Config::set('filesystems.disks.rackspace.key', app('settings')->backup_rackspace_key ? app('settings')->backup_rackspace_key : '');
                        Config::set('filesystems.disks.rackspace.container', app('settings')->backup_rackspace_container ? app('settings')->backup_rackspace_container : '');
                        Config::set('filesystems.disks.rackspace.endpoint', app('settings')->backup_rackspace_endpoint ? app('settings')->backup_rackspace_endpoint : '');
                        Config::set('filesystems.disks.rackspace.region', app('settings')->backup_rackspace_region ? app('settings')->backup_rackspace_region : '');
                        Config::set('filesystems.disks.rackspace.url_type', app('settings')->backup_rackspace_url_type ? app('settings')->backup_rackspace_url_type : '');
                    }

                    if(app('settings')->backup_disks == 'dropbox') {
                        Config::set('filesystems.disks.dropbox.authorizationToken', app('settings')->backup_dropbox_token ? app('settings')->backup_dropbox_token : '');
                    }

                    Config::set('backup.backup.name', app('settings')->site_name);
                    Config::set('backup.backup.gzip_database_dump', app('settings')->backup_gzip_database_dump ? app('settings')->backup_gzip_database_dump : '');
                    Config::set('backup.backup.destination.filename_prefix', app('settings')->backup_filename_prefix ? app('settings')->backup_filename_prefix : '');
                    Config::set('backup.backup.destination.disks', app('settings')->backup_disks ? app('settings')->backup_disks : '');
                    Config::set('backup.monitorBackups.name', app('settings')->site_name ? app('settings')->site_name : '');
                    $monitor[] = [
                        'name' => app('settings')->site_name, '',
                        'disks' => [app('settings')->backup_disks],
                        'newestBackupsShouldNotBeOlderThanDays' => 1,
                        'storageUsedMayNotBeHigherThanMegabytes' => 5000
                    ];
                    Config::set('backup.monitorBackups', $monitor);
                }
            }
        } catch (\Exception $e) {
            //die("Could not connect to the database.  Please check your configuration.");
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }
}