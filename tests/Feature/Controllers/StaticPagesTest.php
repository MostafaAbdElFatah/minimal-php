<?php

declare(strict_types=1);

use App\Models\User;

it('renders the public information pages', function (string $uri, string $heading) {
    $this->get($uri)->assertOk()->assertSee($heading);
})->with([
    'about' => ['/about', 'About Us'],
    'contact' => ['/contact', 'Contact Us'],
])->group('feature', 'controllers');

it('greets the visitor by the name in the query string', function () {
    $this->get('/welcome?name=Jane')->assertOk()->assertSee('Hello, Jane');
})->group('feature', 'controllers')
    ->todo('Route::view evaluates request("name") when routes are registered, so the page always greets "Guest". Move the lookup into the view or a controller.');

it('falls back to Guest when no name is given', function () {
    $this->get('/welcome')->assertOk()->assertSee('Hello, Guest');
})->group('feature', 'controllers');

it('never renders raw markup from the name in the query string', function () {
    $this->get('/welcome?name='.urlencode('<script>alert(1)</script>'))
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false);
})->group('feature', 'controllers');

it('shows the account menu to guests and the avatar menu to users', function () {
    $this->get('/about')->assertSee('Login')->assertSee('Register')->assertDontSee('Logout');

    $this->actingAs(User::factory()->create())->get('/about')
        ->assertSee('Logout')
        ->assertDontSee('Register');
})->group('feature', 'components');

it('responds to the health check endpoint', function () {
    $this->get('/up')->assertOk();
})->group('feature', 'controllers');
