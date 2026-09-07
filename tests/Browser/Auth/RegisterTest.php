<?php

use App\Models\User;

it('registers a new user with valid data', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/');

    expect(User::where('email', 'jane@example.com')->exists())->toBeTrue();
})->group('auth', 'feature');

it('does not register with an already taken email', function () {
    User::factory()->create(['email' => 'jane@example.com']);

    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->assertSee('The email has already been taken.');
})->group('auth', 'feature');

it('does not register when password confirmation does not match', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'different-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->assertSee('The password field confirmation does not match.');
})->group('auth', 'feature');

it('does not register with a missing first name', function () {
    visit(route('register'))
        ->fill('first_name', '')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
;
})->group('auth', 'feature');

it('does not register with a missing last name', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', '')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');

it('does not register with a missing email', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', '')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');

it('does not register with an invalid email format', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'not-an-email')
        ->fill('password', 'jane-password')
        ->fill('password_confirmation', 'jane-password')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');

it('does not register with a missing password', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', '')
        ->fill('password_confirmation', '')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');

it('does not register with a password that is too short', function () {
    visit(route('register'))
        ->fill('first_name', 'Jane')
        ->fill('last_name', 'Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', '123')
        ->fill('password_confirmation', '123')
        ->press('@register')
        ->assertPathIs('/register')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');