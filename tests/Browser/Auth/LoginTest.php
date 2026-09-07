<?php

use App\Models\User;

it('logs in a user with valid credentials', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('jane-password'),
    ]);

    visit(route('login'))
        ->fill('email', 'jane@example.com')
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/');
})->group('auth', 'feature');

it('does not log in with an incorrect password', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('jane-password'),
    ]);

    visit(route('login'))
        ->fill('email', 'jane@example.com')
        ->fill('password', 'wrong-password')
        ->press('@login')
        ->assertPathIs('/login')
        ->assertSee('These credentials do not match our records.');
})->group('auth', 'feature');

it('does not log in with an incorrect email', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('jane-password'),
    ]);

    visit(route('login'))
        ->fill('email', 'wrong@example.com')
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/login')
        ->assertSee('These credentials do not match our records.');
})->group('auth', 'feature');

it('does not log in when the email does not exist at all', function () {
    // No user created — email doesn't exist in the database at all
    visit(route('login'))
        ->fill('email', 'doesnotexist@example.com')
        ->fill('password', 'any-password')
        ->press('@login')
        ->assertPathIs('/login')
        ->assertSee('These credentials do not match our records.');
})->group('auth', 'feature');

it('does not log in with an empty email and password', function () {
    visit(route('login'))
        ->fill('email', 'as')
        ->fill('password', 'as')
        ->press('@login')
        ->assertPathIs('/login')
        ->script('document.querySelector("form").noValidate = true;');
})->group('auth', 'feature');