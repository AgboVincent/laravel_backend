<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PreEvaluations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_evaluations', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('insurer_id');
            // $table->foreign('insurer_id')->references('id')->on('insurers');
            $table->string('name');
            $table->string('email')->uniqid();
            $table->string('chassis_number');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('year')->nullable();
            $table->string('status');
            $table->string('color');
            $table->string('estimate')->nullable();
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
        Schema::dropIfExists('pre_evaluations');
    }
}
