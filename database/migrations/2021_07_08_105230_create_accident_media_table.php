<?php

use App\Models\AccidentMedia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccidentMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accident_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('uploads')->cascadeOnDelete();
            $table->foreignId('accident_id')->constrained('accidents')->cascadeOnDelete();
            $table->enum('type', [
                AccidentMedia::TYPE_CLOSE_UP,
                AccidentMedia::TYPE_WIDE_ANGLE,
                AccidentMedia::TYPE_FRONT,
                AccidentMedia::TYPE_REAR,
                AccidentMedia::TYPE_VIDEO
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accident_media');
    }
}
