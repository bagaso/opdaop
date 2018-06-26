<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateJsonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_jsons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('json_data');
            $table->string('version');
            $table->string('slug_url');
            $table->boolean('is_enable');
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
        Schema::dropIfExists('update_jsons');
    }
}
