<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pam_groups', function (Blueprint $table) {
            $table->increments('dbid');
            $table->string('name')->default('chroot');
            $table->integer('gid')->default(1000);
            $table->string('password')->default('x');
            $table->char('flag', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pam_groups');
    }
}
