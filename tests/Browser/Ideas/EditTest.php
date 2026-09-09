<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;

it('edits an idea from its detail page', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create(['title' => 'Original browser title', 'description' => 'Original browser description.']);

    loginAs($user)
        ->navigate("/ideas/{$idea->id}")
        ->click('@edit')
        ->assertPathIs("/ideas/{$idea->id}/edit")
        ->assertValue('title', 'Original browser title')
        ->fill('title', 'Updated browser title')
        ->select('state', 'complete')
        ->fill('description', 'Updated browser description.')
        ->press('@save')
        ->assertPathIs("/ideas/{$idea->id}")
        ->assertSee('Updated browser title')
        ->assertSee('Updated browser description.')
        ->assertSee('complete')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'title' => 'Updated browser title', 'state' => 'complete']);
})->group('browser', 'controllers');

it('shows a validation error and keeps the original idea when the edit is invalid', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create(['title' => 'Original browser title']);

    loginAs($user)
        ->navigate("/ideas/{$idea->id}/edit")
        ->fill('title', 'ab')
        ->press('@save')
        ->assertPathIs("/ideas/{$idea->id}/edit")
        ->assertSee('The title field must be at least 3 characters.')
        ->assertNoJavaScriptErrors();

    expect($idea->fresh()->title)->toBe('Original browser title');
})->group('browser', 'controllers', 'requests');
