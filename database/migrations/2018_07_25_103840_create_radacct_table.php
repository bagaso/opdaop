<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadacctTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radacct', function (Blueprint $table) {
            $table->bigIncrements('RadAcctId');
            $table->string('AcctSessionId', 32)->default('');
            $table->string('AcctUniqueId', 32)->default('');
            $table->string('UserName', 64)->default('');
            $table->string('Realm', 64)->nullable();
            $table->string('NASIPAddress', 15)->default('');
            $table->string('NASPortId', 15)->nullable();
            $table->string('NASPortType', 32)->nullable();
            $table->dateTime('AcctStartTime')->default('0000-00-00 00:00:00');
            $table->dateTime('AcctStopTime')->default('0000-00-00 00:00:00');
            $table->integer('AcctSessionTime')->nullable();
            $table->string('AcctAuthentic', 32)->nullable();
            $table->string('ConnectInfo_start', 50)->nullable();
            $table->string('ConnectInfo_stop', 50)->nullable();
            $table->bigInteger('AcctInputOctets')->nullable();
            $table->bigInteger('AcctOutputOctets')->nullable();
            $table->string('CalledStationId', 50)->default('');
            $table->string('CallingStationId', 50)->default('');
            $table->string('AcctTerminateCause', 32)->default('');
            $table->string('ServiceType', 32)->nullable();
            $table->string('FramedProtocol', 32)->nullable();
            $table->string('FramedIPAddress', 15)->default('');
            $table->integer('AcctStartDelay', 12)->nullable();
            $table->integer('AcctStopDelay', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radacct');
    }
}
