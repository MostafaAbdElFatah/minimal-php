<?php

use App\Models\Idea;
use App\Models\User;

it('deletes an idea through the browser page', function () {
    $user = User::factory()->create([
        'email' => 'ideas-delete@example.com',
        'password' => bcrypt('password'),
    ]);
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Idea to delete',
    ]);

    $page = visit(route('login'))
        ->fill('email', 'ideas-delete@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate("/ideas/{$idea->id}");

    $page->script("document.querySelector('form[action=\"/ideas/{$idea->id}\"]').submit()");
    $page->assertPathIs('/')
        ->assertDontSee('Idea to delete')
        ->assertNoJavaScriptErrors();

    expect(Idea::find($idea->id))->toBeNull();
})->group('ideas', 'feature');
