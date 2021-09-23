<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->enum('status', [
                \App\Models\ClaimItem::STATUS_PENDING,
                \App\Models\ClaimItem::STATUS_APPROVED,
                \App\Models\ClaimItem::STATUS_REJECTED,
            ])
                ->after('quantity')
                ->default(\App\Models\ClaimItem::STATUS_PENDING);
        });
    }

    public function down()
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
