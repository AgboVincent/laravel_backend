<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewPreevaluationColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_evaluations', function (Blueprint $table) {
            $table->string('address')->after('phone');
            $table->string('usage')->after('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_evaluations', function (Blueprint $table) {
            $table->dropColumn(['address', 'usage']);
        });
    }
}
