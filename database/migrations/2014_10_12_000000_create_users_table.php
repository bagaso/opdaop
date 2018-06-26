<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('service_password');
            $table->string('email')->unique();
            $table->string('fullname');
            $table->text('photo');
            $table->string('contact')->nullable();
            $table->integer('credits')->unsigned()->default(0);
            $table->integer('subscription_id')->unsigned()->default(1);
            $table->double('lifetime_bandwidth')->unsigned()->default(0);
            $table->double('consumable_data')->unsigned()->default(0);
            $table->integer('group_id')->unsigned()->default(5);
            $table->timestamp('seller_first_applied_credit')->nullable();
            $table->boolean('distributor')->unsigned()->default(0);
            $table->integer('max_users')->unsigned()->default(500);
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('parent_id')->unsigned()->default(1);
            $table->timestamp('expired_at')->useCurrent();
            $table->timestamp('freeze_start')->nullable();
            $table->integer('freeze_ctr')->unsigned()->default(0);
            $table->boolean('freeze_mode')->unsigned()->default(0);
            $table->string('dl_speed_openvpn')->default('0kbit');
            $table->string('up_speed_openvpn')->default('0kbit');
            $table->string('password_openvpn')->default(''); // password for openvpn
            $table->string('password_ssh')->default(''); // password for ssh
            $table->boolean('f_login_openvpn')->default(0);
            $table->boolean('f_login_softether')->default(0);
            $table->string('attributes')->default('Cleartext-Password');
            $table->char('op', 2)->default(':=');
            $table->string('value')->default(''); //password for softether
            $table->timestamp('login_datetime')->nullable();
            $table->string('login_ip')->default('0.0.0.0');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
