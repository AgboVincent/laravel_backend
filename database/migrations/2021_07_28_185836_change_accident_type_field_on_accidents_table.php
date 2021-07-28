<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('accidents', function (Blueprint $table) {
            $table->foreignId('accident_type_id')->after('type')
                ->constrained('accident_types')->cascadeOnDelete();
            $table->dropColumn('type');
        });
    }

    public function down()
    {
        Schema::table('accidents', function (Blueprint $table) {
            $table->string('type')->after('accident_type');
            $table->dropColumn('accident_type');
        });
    }
};
