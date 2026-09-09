<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;

it('shows the idea details to its owner with edit and delete actions', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create(['title' => 'Idea details', 'description' => 'Details displayed on the idea page.']);

    loginAs($user)
        ->click('Idea details')
        ->assertPathIs("/ideas/{$idea->id}")
        ->assertSee('Idea details')
        ->assertSee('Details displayed on the idea page.')
        ->assertVisible('@edit')
        ->assertVisible('@delete')
        ->assertNoJavaScriptErrors();
})->group('browser', 'controllers');

it('shows a not found page for another users idea', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->create(['title' => 'Private idea']);

    loginAs($user)
        ->navigate("/ideas/{$idea->id}")
        ->assertSee('404')
        ->assertDontSee('Private idea');
})->group('browser', 'controllers', 'policies');
