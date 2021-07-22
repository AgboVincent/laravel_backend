<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'password' => '$2y$10$S9pHBuc8qFGBwgM9.GxWU./p6w.cq/ESm0LUPAkouAWxY2Tjo9WxW',
        ];
    }
}
