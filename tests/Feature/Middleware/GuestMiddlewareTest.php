<?php

use App\Models\User;
use Tests\TestCase;

test('renders the login page for guests', function () {
    /** @var TestCase $this */
    $response = $this->get('/login');

    $response->assertOk();
    $response->assertSee('Sign in to IdeaHub');
});

test('renders the register page for guests', function () {
    /** @var TestCase $this */
    $response = $this->get('/register');

    $response->assertOk();
    $response->assertSee('Create your IdeaHub account');
});

test('redirects authenticated users away from guest auth pages', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect('/');
});
