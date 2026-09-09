<?php

declare(strict_types=1);

use App\Models\User;

it('redirects guests to the login page', function (string $uri) {
    visit($uri)->assertPathIs('/login')->assertNoJavaScriptErrors();
})->with(['/', '/ideas/create', '/admin'])->group('browser', 'auth', 'middleware');

it('redirects authenticated users away from guest pages', function (string $uri) {
    $user = User::factory()->create(['password' => 'password']);

    loginAs($user)->navigate($uri)->assertPathIs('/')->assertNoJavaScriptErrors();
})->with(['/login', '/register'])->group('browser', 'auth', 'middleware');
