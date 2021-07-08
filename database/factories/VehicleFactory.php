<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'number' => '0000000' . $this->faker->numberBetween(9000, 9999),
            'manufacturer' => $this->faker->company(),
            'model' => $this->faker->text(10),
            'color' => $this->faker->colorName(),
            'gear_type' => $this->faker->randomElement([Vehicle::GEAR_TYPE_AUTO, Vehicle::GEAR_TYPE_MANUEL]),
            'year' => $this->faker->year()
        ];
    }
}
