<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Comment;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'claim_id' => Claim::factory(),
            'comment' => $this->faker->paragraph(),
            'user_id' => User::factory()
        ];
    }
}
