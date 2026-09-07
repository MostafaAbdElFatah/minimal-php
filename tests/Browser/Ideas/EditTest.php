<?php

use App\Models\Idea;
use App\Models\User;

it('edits an idea through the browser form', function () {
    $user = User::factory()->create([
        'email' => 'ideas-edit@example.com',
        'password' => bcrypt('password'),
    ]);
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Original browser title',
        'description' => 'Original browser description.',
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-edit@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate("/ideas/{$idea->id}")
        ->click('Edit')
        ->fill('title', 'Updated browser title')
        ->select('state', 'complete')
        ->fill('description', 'Updated browser description.')
        ->press('Save Changes')
        ->assertPathIs("/ideas/{$idea->id}")
        ->assertSee('Updated browser title')
        ->assertSee('Updated browser description.')
        ->assertNoJavaScriptErrors();

    expect($idea->refresh()->title)->toBe('Updated browser title');
})->group('ideas', 'feature');
