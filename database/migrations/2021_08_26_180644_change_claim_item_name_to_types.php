<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        Schema::table('claim_items', function (Blueprint $table) {
            DB::statement('delete from claim_items where date(created_at) < now()');
            $table->foreignId('type_id')
                ->after('name')->constrained('claim_item_types')
                ->cascadeOnDelete();
            $table->dropColumn('name');
        });
    }

    public function down()
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->dropForeign('claim_items_type_id_foreign');
            $table->dropColumn('type_id');
            $table->string('name', 30)->nullable();
        });
    }
};
