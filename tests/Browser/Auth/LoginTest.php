<?php

declare(strict_types=1);

use App\Models\User;

it('logs in a user with valid credentials', function () {
    $user = User::factory()->create(['password' => 'jane-password']);

    visit(route('login'))
        ->assertNoJavaScriptErrors()
        ->fill('email', $user->email)
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/')
        ->assertSee('Your')
        ->assertSee('Ideas')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');

it('logs in on a mobile viewport', function () {
    $user = User::factory()->create(['password' => 'jane-password']);

    visit(route('login'))->on()->mobile()
        ->assertNoJavaScriptErrors()
        ->fill('email', $user->email)
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');

it('shows the generic credentials error and keeps the email', function (string $email, string $password) {
    User::factory()->create(['email' => 'jane@example.com', 'password' => 'jane-password']);

    visit(route('login'))
        ->fill('email', $email)
        ->fill('password', $password)
        ->press('@login')
        ->assertPathIs('/login')
        ->assertSee('These credentials do not match our records.')
        ->assertValue('email', $email)
        ->assertValue('password', '')
        ->assertNoJavaScriptErrors();
})->with([
    'wrong password' => ['jane@example.com', 'wrong-password'],
    'wrong email' => ['wrong@example.com', 'jane-password'],
    'unknown user' => ['ghost@example.com', 'any-password'],
])->group('browser', 'auth');

it('shows server-side validation errors when the browser validation is bypassed', function () {
    $page = visit(route('login'));
    $page->script('document.querySelector("main form").noValidate = true;');

    $page->fill('email', 'not-an-email')
        ->press('@login')
        ->assertPathIs('/login')
        ->assertSee('The email field must be a valid email address.')
        ->assertSee('The password field is required.')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');

it('toggles password visibility without leaving the page', function () {
    visit(route('login'))
        ->fill('password', 'visible-secret')
        ->assertAttribute('#password', 'type', 'password')
        ->click('[data-password-toggle="password"]')
        ->assertAttribute('#password', 'type', 'text')
        ->assertAttribute('[data-password-toggle="password"]', 'aria-pressed', 'true')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');
