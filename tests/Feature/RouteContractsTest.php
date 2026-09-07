<?php

use Illuminate\Support\Facades\Route;

test('named routes resolve to the expected URIs', function (string $name, string $uri) {
    expect(route($name, absolute: false))->toBe($uri);
})->with([
    'home' => ['home', '/'],
    'login' => ['login', '/login'],
    'register' => ['register', '/register'],
    'logout' => ['logout', '/logout'],
    'destroy all ideas' => ['ideas.destroy-all', '/ideas'],
])->group('feature');

test('protected routes use the expected middleware', function () {
    expect(Route::getRoutes()->getByName('home')->middleware())->toContain('auth')
        ->and(Route::getRoutes()->getByName('login')->middleware())->toContain('guest')
        ->and(Route::getRoutes()->getByName('register')->middleware())->toContain('guest');
})->group('feature');
