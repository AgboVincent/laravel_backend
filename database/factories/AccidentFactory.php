<?php

namespace Database\Factories;

use App\Models\Accident;
use App\Models\AccidentThirdParty;
use App\Models\AccidentType;
use App\Models\Claim;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccidentFactory extends Factory
{
    protected $model = Accident::class;

    public function definition(): array
    {
        return [
            'claim_id' => Claim::factory(),
            'occurred_at' => $this->faker->dateTimeBetween(),
            'accident_type_id' => AccidentType::factory(),
            'description' => $this->faker->paragraphs(10),
            'involved_third_party' => $this->faker->randomElement([1, 0]),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Accident $accident) {
            if ($accident->involved_third_party) {
                AccidentThirdParty::factory()->create([
                    'accident_id' => $accident->id
                ]);
            }
        });
    }
}
