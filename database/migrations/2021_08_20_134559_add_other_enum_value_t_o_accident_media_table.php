<?php

use App\Models\AccidentMedia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('accident_media', function (Blueprint $table) {
            DB::statement("alter table accident_media modify type enum('close up', 'wide angle', 'front', 'rear', 'video', 'other') default 'other' not null");
        });
    }

    public function down()
    {
        Schema::table('accident_media', function (Blueprint $table) {
            DB::statement("alter table accident_media modify type enum('close up', 'wide angle', 'front', 'rear', 'video') not null");
        });
    }
};
