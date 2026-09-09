<?php

declare(strict_types=1);

use App\Models\User;

it('creates an idea through the form', function () {
    $user = User::factory()->create(['password' => 'password']);

    loginAs($user)
        ->click('@new-idea')
        ->assertPathIs('/ideas/create')
        ->assertNoJavaScriptErrors()
        ->fill('title', 'A browser-created idea')
        ->select('state', 'active')
        ->fill('description', 'A description created through the browser form.')
        ->press('@save')
        ->assertPathIs('/')
        ->assertSee('A browser-created idea')
        ->assertSee('(1)')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseHas('ideas', ['title' => 'A browser-created idea', 'state' => 'active', 'user_id' => $user->id]);
})->group('browser', 'controllers');

it('creates an idea on a mobile viewport', function () {
    $user = User::factory()->create(['password' => 'password']);

    visit(route('login'))->on()->mobile()
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create')
        ->fill('title', 'A mobile idea')
        ->select('state', 'draft')
        ->fill('description', 'Created from a phone-sized viewport.')
        ->press('@save')
        ->assertPathIs('/')
        ->assertSee('A mobile idea')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseHas('ideas', ['title' => 'A mobile idea', 'user_id' => $user->id]);
})->group('browser', 'controllers');

it('shows the server-side message for an invalid field and keeps the input', function (array $overrides, string $message) {
    $user = User::factory()->create(['password' => 'password']);
    $form = ['title' => 'A valid idea title', 'description' => 'A valid description for the idea.', ...$overrides];

    $page = loginAs($user)->navigate('/ideas/create')->assertPathIs('/ideas/create');
    $page->script('document.querySelector("main form").noValidate = true;');
    $page->fill('title', $form['title'])->fill('description', $form['description']);
    if (($overrides['state'] ?? 'active') === '') {
        $page->script('document.querySelector("select[name=state]").value = "";');
    } else {
        $page->select('state', $overrides['state'] ?? 'active');
    }

    $page->press('@save')
        ->assertPathIs('/ideas/create')
        ->assertSee($message)
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseCount('ideas', 0);
})->with([
    'missing title' => [['title' => ''], 'The title field is required.'],
    'short title' => [['title' => 'No'], 'The title field must be at least 3 characters.'],
    'long title' => [['title' => str_repeat('a', 256)], 'The title field must not be greater than 255 characters.'],
    'missing description' => [['description' => ''], 'The description field is required.'],
    'short description' => [['description' => 'Too short'], 'The description field must be at least 10 characters.'],
    'missing state' => [['state' => ''], 'The state field is required.'],
])->group('browser', 'controllers', 'requests');
