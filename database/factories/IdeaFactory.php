<?php

namespace Database\Factories;

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'state' => fake()->randomElement(IdeaState::cases()),
            'user_id' => User::inRandomOrder()->value('id'), // User::factory() is creating new users automatically: 
        ];
    }
}
