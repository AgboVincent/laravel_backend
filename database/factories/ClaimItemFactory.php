<?php

namespace Database\Factories;

use App\Models\ClaimItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimItemFactory extends Factory
{
    protected $model = ClaimItem::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
            'quantity' => $this->faker->randomDigitNotZero(),
            'amount' => $this->faker->randomDigitNotZero() * $this->faker->randomDigitNotZero() * 1000,
            'status' => $this->faker->randomElement([
                ClaimItem::STATUS_APPROVED,
                ClaimItem::STATUS_PENDING,
                ClaimItem::STATUS_REJECTED
            ])
        ];
    }
}
