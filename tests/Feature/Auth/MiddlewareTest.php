<?php

use App\Models\User;
use Tests\TestCase;

it('redirects guests to login from the home page', function () {
    /** @var TestCase $this */
    $this->get(route('home'))->assertRedirect(route('login'));
})->group('auth', 'feature');

it('redirects guests to login from the create idea page', function () {
    /** @var TestCase $this */
    $this->get('/ideas/create')->assertRedirect(route('login'));
})->group('auth', 'feature');

it('redirects guests to login when they log out', function () {
    /** @var TestCase $this */
    $this->delete(route('logout'))->assertRedirect(route('login'));
})->group('auth', 'feature');

it('redirects authenticated users away from registration', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->get(route('register'));

    $response->assertRedirect('/');
})->group('auth', 'feature');
