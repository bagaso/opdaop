<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->unique()->default('uuid()');;
            $table->string('code')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('created_user_id');
            $table->unsignedInteger('duration');
            $table->timestamps();
            $table->index(['user_id', 'created_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
