<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadgroupcheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radgroupcheck', function (Blueprint $table) {
            $table->increments('id');
            $table->string('GroupName', 64)->default('');
            $table->string('Attribute', 32)->default('');
            $table->char('op', 2)->default('==');
            $table->string('Value', 253)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radgroupcheck');
    }
}
