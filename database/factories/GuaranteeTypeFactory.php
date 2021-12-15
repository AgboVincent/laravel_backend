<?php

namespace Database\Factories;

use App\Models\GuaranteeType;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuaranteeTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuaranteeType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}
