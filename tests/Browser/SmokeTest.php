<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;

it('renders every public page without smoke', function () {
    visit(['/login', '/register', '/about', '/contact', '/welcome'])->assertNoSmoke();
})->group('browser');

it('renders every authenticated page without smoke', function () {
    $user = User::factory()->create(['password' => 'password']);
    $idea = Idea::factory()->for($user)->create();

    loginAs($user);

    visit(['/', '/ideas/create', "/ideas/{$idea->id}", "/ideas/{$idea->id}/edit"])->assertNoSmoke();
})->group('browser');

it('renders the login page in dark mode and on mobile without smoke', function () {
    visit('/login')->inDarkMode()->assertNoSmoke();
    visit('/login')->on()->mobile()->assertNoSmoke();
})->group('browser');
