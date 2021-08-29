<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            DB::statement(
                "alter table claims modify status enum('pending','approved','declined','attention requested','awaiting payment','completed') not null default 'pending'"
            );
        });
    }

    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            DB::statement(
                "alter table claims modify status enum('pending','approved','declined','attention requested') not null default 'pending'"
            );
        });
    }
};
