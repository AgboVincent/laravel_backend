<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PreEvaluationFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_evaluation_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_evaluation_id')->constrained();
            $table->foreignId('type_id')->references('id')->on('vehicle_types');
            $table->string('vehicle_part')->nullable()
            $table->string('url');
            $table->string('processing_status');
            $table->string('result');
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
        Schema::dropIfExists('pre_evaluation_files');
    }
}
