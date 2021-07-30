<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Policy;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'number' => $this->faker->unique(true)->numberBetween(100000, 999999),
            'company_id' => Company::factory(),
            'expires_at' => now()->addMonths(2),
            'type' => $this->faker->randomElement([
                Policy::TYPE_THIRD_PARTY,
                Policy::TYPE_COMPREHENSIVE
            ]),
            'status' => $this->faker->randomElement([
                Policy::STATUS_ACTIVE,
                Policy::STATUS_EXPIRED
            ])
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Policy $policy) {
            return Vehicle::factory()->createOne(['policy_id' => $policy->id]);
        });
    }
}
