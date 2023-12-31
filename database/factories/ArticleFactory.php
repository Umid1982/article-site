<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first();
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
            'image_path' => fake()->filePath(),
            'user_id' => $user->id,
            'likes_count' => fake()->numberBetween(1, 50),
            'shows_count' => fake()->numberBetween(1, 100),
        ];
    }
}
