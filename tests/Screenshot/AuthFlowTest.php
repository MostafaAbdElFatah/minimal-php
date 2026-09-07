<?php

it('completes registration, logout, and login through the browser', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane-browser@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/')
        ->assertSee('Your Ideas')
        ->assertNoJavaScriptErrors()
        ->click('[data-menu="account"] [role="button"]')
        ->press('@Logout')
        ->assertPathIs('/login')
        ->fill('email', 'jane-browser@example.com')
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/')
        ->assertSee('Your Ideas')
        ->assertNoJavaScriptErrors();
})->group('auth', 'feature');
