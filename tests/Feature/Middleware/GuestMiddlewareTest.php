<?php

declare(strict_types=1);

use App\Models\User;

it('redirects authenticated users home from guest-only routes', function (string $method, string $uri) {
    $this->actingAs(User::factory()->create())->{$method}($uri)->assertRedirect('/');
})->with([
    'login form' => ['get', '/login'],
    'login submit' => ['post', '/login'],
    'register form' => ['get', '/register'],
    'register submit' => ['post', '/register'],
])->group('feature', 'middleware', 'auth');

it('lets guests reach the guest-only pages', function (string $uri) {
    $this->get($uri)->assertOk();
})->with(['/login', '/register'])->group('feature', 'middleware', 'auth');
