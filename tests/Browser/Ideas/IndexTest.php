<?php

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;

it('lists all ideas owned by the authenticated user', function () {
    $user = User::factory()->create([
        'email' => 'ideas-index@example.com',
        'password' => bcrypt('password'),
    ]);
    Idea::factory()->for($user)->create([
        'title' => 'First browser idea',
        'description' => 'The first idea description.',
        'state' => IdeaState::ACTIVE,
    ]);
    Idea::factory()->for($user)->create([
        'title' => 'Second browser idea',
        'description' => 'The second idea description.',
        'state' => IdeaState::PENDING,
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-index@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->assertPathIs('/')
        ->assertSee('First browser idea')
        ->assertSee('Second browser idea')
        ->assertSee('(2)')
        ->assertNoJavaScriptErrors();
})->group('ideas', 'feature');
