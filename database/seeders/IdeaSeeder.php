<?php

namespace Database\Seeders;

use App\Models\Idea;
use Illuminate\Database\Seeder;

class IdeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Idea::factory()->count(100)->create();
        //Idea::factory()->count(20)->create(['user_id' => 2]);
    }
}
