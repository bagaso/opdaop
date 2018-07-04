<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class');
            $table->string('name');
            $table->integer('cost')->unsigned()->default(1);
            $table->double('data')->unsigned()->default(0);
            $table->integer('device')->unsigned()->default(1);
            $table->integer('min_credits')->unsigned()->default(1);
            $table->integer('max_credits')->unsigned()->default(1);
            $table->integer('min_duration')->unsigned()->default(1);
            $table->string('dl_speed_openvpn')->default('0kbit');
            $table->string('up_speed_openvpn')->default('0kbit');
            $table->boolean('login_openvpn')->unsigned()->default(0);
            $table->boolean('login_ssh')->unsigned()->default(0);
            $table->boolean('login_softether')->unsigned()->default(0);
            $table->boolean('login_ss')->unsigned()->default(0);
            $table->boolean('is_public')->unsigned()->default(0);
            $table->boolean('is_enable')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
