<?php

namespace Database\Factories;

use App\Models\Policy;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'policy_id' => Policy::factory(),
            'registration_number' => time() . $this->faker->unique(true)->numberBetween(100, 999),
            'chassis_number' => time() . $this->faker->unique(true)->numberBetween(100, 999),
            'engine_number' => time() . $this->faker->unique(true)->numberBetween(100, 999),
            'manufacturer' => $this->faker->company(),
            'estimate' => 800000,
            'model' => $this->faker->text(10),
            'color' => $this->faker->colorName(),
            'gear_type' => $this->faker->randomElement([Vehicle::GEAR_TYPE_AUTO, Vehicle::GEAR_TYPE_MANUEL]),
            'year' => $this->faker->year()
        ];
    }
}
