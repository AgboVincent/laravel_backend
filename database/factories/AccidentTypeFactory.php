<?php

namespace Database\Factories;

use App\Models\AccidentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccidentTypeFactory extends Factory
{
    protected $model = AccidentType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->text()
        ];
    }
}
