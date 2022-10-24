<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_evaluation_id')->references('id')->on('pre_evaluations');
            $table->foreignId('policy_id')->references('id')->on('new_policies');
            $table->string('purchased_policy');
            $table->string('payment_status')->nullable();
            $table->string('evaluation_status')->nullable();
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
        Schema::dropIfExists('purchased_policies');
    }
}
