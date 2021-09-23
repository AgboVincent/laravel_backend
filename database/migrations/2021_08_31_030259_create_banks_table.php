<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 6)->unique();
            $table->string('country')->default('Nigeria');
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
};
