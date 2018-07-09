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
            $table->uuid('id');
            $table->integer('user_id')->unsigned();
            $table->string('protocol');
            $table->integer('server_id')->unsigned();
            $table->string('user_ip')->default('0.0.0.0');
            $table->string('user_port')->default('0');
            $table->double('byte_sent')->unsigned()->default(0);            //Download
            $table->double('byte_received')->unsigned()->default(0);        //Upload
            $table->double('data_available')->unsigned()->default(0);       //Data Allowance for this session
            $table->timestamps();
            $table->primary('id');
            $table->index(['user_id', 'server_id']);
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
