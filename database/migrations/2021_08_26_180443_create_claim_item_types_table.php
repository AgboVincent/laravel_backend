<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('claim_item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('key', 50)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_item_types');
    }
};
