<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_evaluation_id')->references('id')->on('pre_evaluations');
            $table->foreignId('type_id')->references('id')->on('vehicle_types');
            $table->string('url');
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
        Schema::dropIfExists('collection_files');
    }
}
