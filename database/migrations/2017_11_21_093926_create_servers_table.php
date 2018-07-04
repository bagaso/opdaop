<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cf_id')->unique();
            $table->string('server_type')->default('vpn');
            $table->integer('server_access_id')->unsigned()->default(2);
            $table->ipAddress('server_ip')->default('0.0.0.0');
            $table->string('server_name')->unique();
            $table->string('manager_port')->default('8000');
            $table->string('manager_password')->default('abcd1234');
            $table->string('server_key')->unique();
            $table->string('sub_domain')->unique();
            $table->integer('web_port')->default(80);
            $table->string('dl_speed_openvpn')->default('0kbit');
            $table->string('up_speed_openvpn')->default('0kbit');
            $table->boolean('limit_bandwidth')->unsigned()->default(0);
            $table->boolean('is_active')->unsigned()->default(0);
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
        Schema::dropIfExists('servers');
    }
}
