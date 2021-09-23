<?php

namespace Database\Factories;

use App\Models\Accident;
use App\Models\AccidentThirdParty;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccidentThirdPartyFactory extends Factory
{
    protected $model = AccidentThirdParty::class;

    public function definition(): array
    {
        return [
            'accident_id' => Accident::factory(),
            'full_name' => $this->faker->name(),
            'mobile' => '08123456789',
            'company' => $this->faker->company(),
            'policy_number' => '00000000000'
        ];
    }
}
