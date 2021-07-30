<?php

namespace Database\Factories;

use App\Models\Accident;
use App\Models\Claim;
use App\Models\Policy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'policy_id' => Policy::factory(),
            'status' => $this->faker->randomElement([
                Claim::STATUS_PENDING,
                Claim::STATUS_DECLINED,
                Claim::STATUS_ATTENTION_REQUESTED,
                Claim::STATUS_APPROVED,
            ])
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Claim $claim) {
            Accident::factory()->create([
                'claim_id' => $claim->id
            ]);
        });
    }


}
