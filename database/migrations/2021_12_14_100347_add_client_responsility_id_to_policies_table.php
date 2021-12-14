<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientResponsilityIdToPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_responsibilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('value', 5, 2, true);
            $table->timestamps();
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->unsignedBigInteger('client_responsibility_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_responsibilities');

        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('client_responsibility_id');
        });
    }
}
