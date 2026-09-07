<?php

use Tests\TestCase;

it('registers a user through the rendered browser flow', function () {
    /** @var TestCase $this */
    $this->visit('/register')
        ->type('input[name="first_name"]', 'Jane')
        ->type('input[name="last_name"]', 'Doe')
        ->type('input[name="email"]', 'jane-browser@example.com')
        ->type('input[name="password"]', 'password')
        ->type('input[name="password_confirmation"]', 'password')
        ->click('Create account')
        ->assertPathIs('/')
        ->assertSee('Your Ideas')
        ->assertNoJavaScriptErrors()
        ->screenshot(true, 'registration-flow');
})->group('browser', 'slow');
