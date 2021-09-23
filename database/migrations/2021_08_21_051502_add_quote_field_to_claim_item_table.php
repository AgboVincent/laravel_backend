<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->double('quote', 20)->after('status')->default(0);
        });
    }

    public function down()
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->dropColumn('quote');
        });
    }
};
