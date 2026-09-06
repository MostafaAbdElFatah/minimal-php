<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Seeder;

class IdeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Idea::factory()->count(100)->create([
            'user_id' => User::inRandomOrder()->value('id'),
        ]);
        Idea::factory()->count(20)->create(['user_id' => 2]);
    }
}
