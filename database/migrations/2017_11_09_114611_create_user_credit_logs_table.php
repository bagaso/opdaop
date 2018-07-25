<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->default(DB::raw('uuid()'));
            $table->integer('user_id')->index();
            $table->integer('user_id_related')->index();
            $table->string('type');
            $table->string('direction');
            $table->integer('credit_used');
            $table->string('duration');
            $table->string('credit_before');
            $table->string('credit_after');
            $table->timestamps();
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_credit_logs');
    }
}
