<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

test('named routes resolve to the expected URIs', function (string $name, string $uri) {
    expect(route($name, absolute: false))->toBe($uri);
})->with([
    'home' => ['home', '/'],
    'login' => ['login', '/login'],
    'register' => ['register', '/register'],
    'logout' => ['logout', '/logout'],
    'destroy all ideas' => ['ideas.destroy-all', '/ideas'],
])->group('feature', 'middleware');

test('routes carry the expected middleware', function (string $name, string $middleware) {
    expect(Route::getRoutes()->getByName($name)->middleware())->toContain($middleware);
})->with([
    'home requires auth' => ['home', 'auth'],
    'logout requires auth' => ['logout', 'auth'],
    'destroy all requires auth' => ['ideas.destroy-all', 'auth'],
    'login is guest only' => ['login', 'guest'],
    'register is guest only' => ['register', 'guest'],
])->group('feature', 'middleware');

test('the admin page is guarded by auth and the view-admin gate', function () {
    $route = collect(Route::getRoutes()->getRoutes())->first(fn ($route): bool => $route->uri() === 'admin');

    expect($route->middleware())->toContain('auth')->toContain('can:view-admin');
})->group('feature', 'middleware', 'policies');

test('the edit route is guarded by the update ability', function () {
    $route = collect(Route::getRoutes()->getRoutes())->first(fn ($route): bool => $route->uri() === 'ideas/{idea}/edit');

    expect($route->middleware())->toContain('can:update,idea');
})->group('feature', 'middleware', 'policies');
