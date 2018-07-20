<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_credit_logs', function (Blueprint $table) {
            $table->uuid('id')->unique()->default('uuid()');;
            $table->integer('user_id_from')->index();
            $table->integer('user_id_to')->index();
            $table->string('type');
            $table->integer('credit_used');
            $table->string('credit_before_from');
            $table->string('credit_after_from');
            $table->string('credit_before_to');
            $table->string('credit_after_to');
            $table->timestamp('duration_before')->nullable();
            $table->timestamp('duration_after')->nullable();
            $table->string('duration');
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
        Schema::dropIfExists('admin_credit_logs');
    }
}
