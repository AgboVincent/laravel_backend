<?php

use App\Models\Car;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('number', 13)->unique()->index();
            $table->string('manufacturer', 190)->index();
            $table->string('model', 100)->index();
            $table->string('color')->nullable();
            $table->enum('gear_type', [Car::GEAR_TYPE_AUTO, Car::GEAR_TYPE_MANUEL])->default(Car::GEAR_TYPE_AUTO);
            $table->integer('year')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
