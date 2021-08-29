<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        \App\Models\ClaimItem::query()->whereDate('created_at', '<', now())->delete();
        Schema::table('claim_items', function (Blueprint $table) {
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
