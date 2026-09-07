<?php

use App\Models\Idea;
use App\Models\User;

it('shows an idea when its owner opens it', function () {
    $user = User::factory()->create([
        'email' => 'ideas-show@example.com',
        'password' => bcrypt('password'),
    ]);
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Idea details',
        'description' => 'Details displayed on the idea page.',
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-show@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate("/ideas/{$idea->id}")
        ->assertSee('Idea details')
        ->assertSee('Details displayed on the idea page.')
        ->assertSee('Edit')
        ->assertSee('Delete')
        ->assertNoJavaScriptErrors();
})->group('ideas', 'feature');
