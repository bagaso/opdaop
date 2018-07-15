<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('user_id')->index();
            $table->string('protocol');
            $table->integer('server_id')->index();
            $table->string('user_ip')->default('0.0.0.0');
            $table->string('user_port')->default('0');
            $table->double('byte_sent')->unsigned()->default(0);            //Download
            $table->double('byte_received')->unsigned()->default(0);        //Upload
            $table->double('data_available')->unsigned()->default(0);       //Data Allowance for this session
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
        Schema::dropIfExists('online_users');
    }
}
