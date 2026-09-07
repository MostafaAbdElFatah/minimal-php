<?php

use App\Models\User;
use Tests\TestCase;

it('redirects guests to login from the home page', function () {
    visit(route('home'))->assertUrlIs(route('login'));
})->group('auth', 'feature');

it('redirects guests to login from the create idea page', function () {
    visit('/ideas/create')->assertUrlIs(route('login'));
})->group('auth', 'feature');

it('redirects authenticated users away from registration', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->get(route('register'));

    $response->assertRedirect('/');
})->group('auth', 'feature');