<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;

it('deletes an idea from its detail page after confirming', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create(['title' => 'Idea to delete']);

    $page = loginAs($user)->navigate("/ideas/{$idea->id}")->assertPathIs("/ideas/{$idea->id}");
    $page->script('window.confirm = () => true;');

    $page->press('@delete')
        ->assertPathIs('/')
        ->assertDontSee('Idea to delete')
        ->assertSee('No ideas yet')
        ->assertNoJavaScriptErrors();

    $this->assertModelMissing($idea);
})->group('browser', 'controllers');

it('keeps the idea when the confirmation is cancelled', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create(['title' => 'Idea to keep']);

    $page = loginAs($user)->navigate("/ideas/{$idea->id}")->assertPathIs("/ideas/{$idea->id}");
    $page->script('window.confirm = () => false;');

    $page->press('@delete')
        ->assertPathIs("/ideas/{$idea->id}")
        ->assertSee('Idea to keep')
        ->assertNoJavaScriptErrors();

    $this->assertModelExists($idea);
})->group('browser', 'controllers');

it('deletes every idea from the list after confirming', function () {
    $user = User::factory()->create(['password' => 'password']);
    Idea::factory()->count(3)->for($user)->create();

    $page = loginAs($user)->assertSee('(3)');
    $page->script('window.confirm = () => true;');

    $page->press('@delete-all')
        ->assertPathIs('/')
        ->assertSee('No ideas yet')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseCount('ideas', 0);
})->group('browser', 'controllers');
