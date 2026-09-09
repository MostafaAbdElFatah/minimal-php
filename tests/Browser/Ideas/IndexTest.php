<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;

it('lists the users ideas with their count', function () {
    $user = User::factory()->create(['password' => 'password']);
    Idea::factory()->for($user)->create(['title' => 'First browser idea', 'state' => IdeaState::ACTIVE]);
    Idea::factory()->for($user)->create(['title' => 'Second browser idea', 'state' => IdeaState::PENDING]);
    Idea::factory()->create(['title' => 'Someone elses idea']);

    loginAs($user)
        ->assertSee('First browser idea')
        ->assertSee('Second browser idea')
        ->assertDontSee('Someone elses idea')
        ->assertSee('(2)')
        ->assertNoJavaScriptErrors();
})->group('browser', 'controllers');

it('filters the list when a state is picked and clears it again', function () {
    $user = User::factory()->create(['password' => 'password']);
    Idea::factory()->for($user)->create(['title' => 'Active idea', 'state' => IdeaState::ACTIVE]);
    Idea::factory()->for($user)->create(['title' => 'Pending idea', 'state' => IdeaState::PENDING]);

    loginAs($user)
        ->select('#state', 'active')
        ->assertQueryStringHas('state', 'active')
        ->assertSee('Active idea')
        ->assertDontSee('Pending idea')
        ->assertSee('(1)')
        ->click('Clear')
        ->assertPathIs('/')
        ->assertSee('Pending idea')
        ->assertNoJavaScriptErrors();
})->group('browser', 'controllers');

it('pages through more than ten ideas', function () {
    $user = User::factory()->create(['password' => 'password']);
    Idea::factory()->count(11)->for($user)->create();

    loginAs($user)
        ->assertSee('(11)')
        ->click('[aria-label="Next page"]')
        ->assertQueryStringHas('page', '2')
        ->assertNoJavaScriptErrors();
})->group('browser', 'controllers');
