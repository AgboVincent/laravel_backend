<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_evaluation_id')->references('id')->on('pre_evaluations');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->foreignId('purchased_policy_id')->references('id')->on('purchased_policies');
            $table->string('landmark')->nullable();
            $table->foreignId('accident_id')->references('id')->on('accident_types');
            $table->text('description');
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
        Schema::dropIfExists('collections');
    }
}
