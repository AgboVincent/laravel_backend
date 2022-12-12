<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluationDamages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detected_damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_evaluation_id')->references('id')->on('pre_evaluations');
            $table->json('front')->nullable();
            $table->json('rear')->nullable();
            $table->json('left')->nullable();
            $table->json('right')->nullable();
            $table->json('front_box')->nullable();
            $table->json('rear_box')->nullable();
            $table->json('left_box')->nullable();
            $table->json('right_box')->nullable();
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
        Schema::dropIfExists('detected_damages');
    }
}
