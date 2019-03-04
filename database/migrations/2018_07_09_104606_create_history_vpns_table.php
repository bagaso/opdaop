<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryVpnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_vpns', function (Blueprint $table) {
            $table->uuid('id')->unique()->default(DB::raw('uuid()'));
            $table->integer('user_id')->index();
            $table->string('protocol');
            $table->ipAddress('user_ip')->default('0.0.0.0');
            $table->string('user_port')->default('0');
            $table->string('server_name');
            $table->string('server_ip');
            $table->string('sub_domain');
            $table->double('byte_sent')->default(0)->unsigned();            //Download
            $table->double('byte_received')->default(0)->unsigned();        //Upload
            $table->timestamp('session_start');
            $table->timestamp('session_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_vpns');
    }
}
