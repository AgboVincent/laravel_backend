<?php

use App\Models\Vehicle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->cascadeOnDelete();
            $table->string('registration_number', 13)->unique()->index();
            $table->string('chassis_number', 20)->unique()->index();
            $table->string('engine_number', 20)->unique()->index();
            $table->string('manufacturer', 190)->index();
            $table->string('model', 100)->index();
            $table->float('estimate', 15)->default(0);
            $table->string('color')->nullable();
            $table->enum('gear_type', [Vehicle::GEAR_TYPE_AUTO, Vehicle::GEAR_TYPE_MANUEL])
                ->default(Vehicle::GEAR_TYPE_AUTO);
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
        Schema::dropIfExists('vehicles');
    }
}
