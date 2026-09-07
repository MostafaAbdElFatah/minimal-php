<?php

use App\Models\Idea;
use App\Models\User;

it('creates an idea through the browser form', function () {
    User::factory()->create([
        'email' => 'ideas-create@example.com',
        'password' => bcrypt('password'),
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-create@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create')
        ->fill('title', 'A browser-created idea')
        ->select('state', 'active')
        ->fill('description', 'A description created through the browser form.')
        ->press('Save')
        ->assertPathIs('/')
        ->assertSee('A browser-created idea')
        ->assertNoJavaScriptErrors();

    expect(Idea::where('title', 'A browser-created idea')->exists())->toBeTrue();
})->group('ideas', 'feature');

it('does not create an idea without a title', function () {
    User::factory()->create([
        'email' => 'ideas-create-required-title@example.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'))
        ->fill('email', 'ideas-create-required-title@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create');

    $page->script('document.querySelector("form").noValidate = true;');
    $page->fill('title', '')
        ->fill('description', 'A valid description for the idea.')
        ->select('state', 'active')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The title field is required.');
})->group('ideas', 'feature');

it('does not create an idea with a title that is too short', function () {
    User::factory()->create([
        'email' => 'ideas-create-short-title@example.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'))
        ->fill('email', 'ideas-create-short-title@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create');

    $page->fill('title', 'No')
        ->fill('description', 'A valid description for the idea.')
        ->select('state', 'active')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The title field must be at least 3 characters.');
})->group('ideas', 'feature');

it('does not create an idea without a description', function () {
    User::factory()->create([
        'email' => 'ideas-create-required-description@example.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'))
        ->fill('email', 'ideas-create-required-description@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create');

    $page->script('document.querySelector("form").noValidate = true;');
    $page->fill('title', 'A valid idea title')
        ->fill('description', '')
        ->select('state', 'active')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The description field is required.');
})->group('ideas', 'feature');

it('does not create an idea with a description that is too short', function () {
    User::factory()->create([
        'email' => 'ideas-create-short-description@example.com',
        'password' => bcrypt('password'),
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-create-short-description@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create')
        ->fill('title', 'A valid idea title')
        ->fill('description', 'Too short')
        ->select('state', 'active')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The description field must be at least 10 characters.');
})->group('ideas', 'feature');

it('does not create an idea without a state', function () {
    User::factory()->create([
        'email' => 'ideas-create-required-state@example.com',
        'password' => bcrypt('password'),
    ]);

    $page = visit(route('login'))
        ->fill('email', 'ideas-create-required-state@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create');

    $page->script('document.querySelector("form").noValidate = true; document.querySelector("select[name=state]").value = "";');
    $page->fill('title', 'A valid idea title')
        ->fill('description', 'A valid description for the idea.')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The state field is required.');
})->group('ideas', 'feature');

it('does not create an idea with a title that is too long', function () {
    User::factory()->create([
        'email' => 'ideas-create-long-title@example.com',
        'password' => bcrypt('password'),
    ]);

    visit(route('login'))
        ->fill('email', 'ideas-create-long-title@example.com')
        ->fill('password', 'password')
        ->press('@login')
        ->navigate('/ideas/create')
        ->fill('title', str_repeat('a', 256))
        ->fill('description', 'A valid description for the idea.')
        ->select('state', 'active')
        ->press('Save')
        ->assertPathIs('/ideas/create')
        ->assertSee('The title field must not be greater than 255 characters.');
})->group('ideas', 'feature');
