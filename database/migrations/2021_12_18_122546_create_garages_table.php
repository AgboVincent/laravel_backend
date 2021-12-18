<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->string('description', 190)->nullable();
            $table->string('email', 190);
            $table->string('phone', 190)->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->foreignId('garage_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garages');

        Schema::table('claims', function (Blueprint $table) {
            $table->dropConstrainedForeignId('garage_id');
        });
    }
}
