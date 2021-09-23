<?php

namespace Database\Factories;

use App\Models\ClaimItem;
use App\Models\ClaimItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimItemFactory extends Factory
{
    protected $model = ClaimItem::class;

    public function definition(): array
    {
        return [
            'type_id' => ClaimItemType::query()->first()->id,
            'quantity' => $this->faker->randomDigitNotZero(),
            'quote' => $this->faker->randomDigitNotZero() * $this->faker->randomDigitNotZero() * 1000,
            'amount' => $this->faker->randomDigitNotZero() * $this->faker->randomDigitNotZero() * 1000,
            'status' => $this->faker->randomElement([
                ClaimItem::STATUS_APPROVED,
                ClaimItem::STATUS_PENDING,
                ClaimItem::STATUS_REJECTED
            ])
        ];
    }
}
