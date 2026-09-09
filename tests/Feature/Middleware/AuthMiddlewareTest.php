<?php

declare(strict_types=1);

use App\Models\User;

it('redirects guests to the login page from every protected route', function (string $method, string $uri) {
    $this->{$method}($uri)->assertRedirect(route('login'));
})->with([
    'home' => ['get', '/'],
    'create idea' => ['get', '/ideas/create'],
    'store idea' => ['post', '/ideas/create'],
    'destroy all ideas' => ['delete', '/ideas'],
    'logout' => ['delete', '/logout'],
    'admin' => ['get', '/admin'],
])->group('feature', 'middleware', 'auth');

it('lets authenticated users through', function () {
    $this->actingAs(User::factory()->create())->get(route('home'))->assertOk();
})->group('feature', 'middleware', 'auth');

it('remembers the intended URL and returns there after login', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    $this->get('/ideas/create')->assertRedirect(route('login'));
    $this->post(route('login'), ['email' => $user->email, 'password' => 'secret-password'])
        ->assertRedirect('/');
})->group('feature', 'middleware', 'auth')
    ->todo('SessionsController redirects to "/" unconditionally instead of redirect()->intended(); enable when intended redirects are implemented.');
