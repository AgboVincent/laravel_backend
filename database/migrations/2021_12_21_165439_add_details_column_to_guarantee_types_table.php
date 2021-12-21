<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsColumnToGuaranteeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guarantee_types', function (Blueprint $table) {
            $table->json('details')->after('name')->nullable();
            $table->renameColumn('name', 'code')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guarantee_types', function (Blueprint $table) {
            $table->dropColumn('details');
            $table->renameColumn('code', 'name');
        });
    }
}
