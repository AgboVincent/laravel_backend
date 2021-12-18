<?php

namespace Database\Factories;

use App\Models\Garage;
use Illuminate\Database\Eloquent\Factories\Factory;

class GarageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Garage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'address' => [
                'line_1' => $this->faker->streetAddress(),
                'line_2' => $this->faker->secondaryAddress(),
                'line_3' => $this->faker->secondaryAddress(),
                'city' => $this->faker->city(),
                'postal_code' => $this->faker->postcode(),
                'code_pay_iso_2' => $this->faker->word(),
                'longitude' => $this->faker->longitude(),
                'latitude' => $this->faker->latitude(),
            ],
        ];
    }
}
