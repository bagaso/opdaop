<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name')->default('VPN Panel');
            $table->string('site_url')->default('http://domain.com/');
            $table->string('cf_zone')->default('');
            $table->boolean('maintenance_mode')->default(true);
            $table->boolean('enable_backup')->default(false);
            $table->string('backup_cron')->default('0 */12 * * *');
            $table->string('queue_driver')->default('beanstalkd');
            $table->string('trial_period')->default('2h');
            $table->boolean('enable_data_reset')->default(true);
            $table->string('data_reset_cron')->default('0 0 * * *');
            $table->string('data_allowance')->default('100mb');
            $table->boolean('public_online_users')->default(false);
            $table->boolean('enable_online_users')->default(true);
            $table->boolean('public_server_status')->default(false);
            $table->boolean('enable_server_status')->default(true);
            $table->boolean('enable_authorized_reseller')->default(true);
            $table->boolean('public_authorized_reseller')->default(false);
            $table->integer('max_credit_transfer')->default(50);
            $table->boolean('enable_public_registration')->default(false);
            $table->boolean('enable_account_creation')->default(true);
            $table->integer('renewal_qualified')->default(10);
            $table->integer('max_time_lapse_renewal')->default(30);
            $table->integer('max_vacation_input')->default(3);
            $table->string('mail_driver')->default('');
            $table->string('mail_host')->default('');
            $table->integer('mail_port')->default(0);
            $table->string('mail_secret_code')->default('');
            $table->string('mail_encryption')->default('');
            $table->string('mail_from_address')->default('');
            $table->string('mail_from_name')->default('');
            $table->string('backup_filename_prefix')->default('');
            $table->boolean('backup_gzip_database_dump')->default(false);
            $table->string('backup_disks')->default('');
            $table->string('backup_ftp_host')->default('');
            $table->string('backup_ftp_username')->default('');
            $table->string('backup_ftp_password')->default('');
            $table->string('backup_aws_key')->default('');
            $table->string('backup_aws_secret')->default('');
            $table->string('backup_aws_region')->default('');
            $table->string('backup_aws_bucket')->default('');
            $table->string('backup_rackspace_username')->default('');
            $table->string('backup_rackspace_key')->default('');
            $table->string('backup_rackspace_container')->default('');
            $table->string('backup_rackspace_endpoint')->default('');
            $table->string('backup_rackspace_region')->default('');
            $table->string('backup_rackspace_url_type')->default('');
            $table->string('backup_dropbox_token')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
