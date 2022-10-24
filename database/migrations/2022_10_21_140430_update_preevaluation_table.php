<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePreevaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_evaluations', function (Blueprint $table) {
            $table->dropColumn(['status', 'estimate', 'evaluation_status']);
            $table->string('phone')->after('email');
            $table->string('vehicle_regno')->after('manufacturer');
            $table->string('engine_no')->after('vehicle_regno');
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
            $table->string('status');
            $table->string('estimate');
            $table->string('evaluation_status');
            $table->dropColumn(['phone', 'vehicle_regno', 'engine_no']);
        });
        
    }
}
