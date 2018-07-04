<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class');
            $table->string('name');
            $table->integer('max_users')->unsigned();
            $table->integer('max_device')->unsigned();
            $table->boolean('is_paid')->unsigned()->default(1);
            $table->boolean('is_private')->unsigned()->default(0);
            $table->boolean('multi_login')->unsigned()->default(0);
            $table->boolean('is_public')->unsigned()->default(0);
            $table->boolean('is_active')->unsigned()->default(0);
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
        Schema::dropIfExists('server_accesses');
    }
}
