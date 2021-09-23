<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => '08123456789',
            'type' => $this->faker->randomElement([
                User::TYPE_POLICY_HOLDER,
                User::TYPE_BROKER,
                User::TYPE_INSURANCE
            ]),
            'company_id' => Company::factory(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            Policy::factory()->createOne(['user_id' => $user->id, 'company_id' => $user->company->id]);
        });
    }
}
