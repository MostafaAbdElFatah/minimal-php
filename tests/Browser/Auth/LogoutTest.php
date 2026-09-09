<?php

declare(strict_types=1);

use App\Models\User;

it('registers, logs out from the account menu and logs back in', function () {
    $page = visit(route('register'));
    foreach (['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane-browser@example.com', 'password' => 'jane-password', 'password_confirmation' => 'jane-password'] as $field => $value) {
        $page->fill($field, $value);
    }

    $page->press('@register')
        ->assertPathIs('/')
        ->assertNoJavaScriptErrors()
        ->click('[data-menu="account"] [role="button"]')
        ->press('@Logout')
        ->assertPathIs('/login')
        ->fill('email', 'jane-browser@example.com')
        ->fill('password', 'jane-password')
        ->press('@login')
        ->assertPathIs('/')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');

it('cannot reach protected pages after logging out', function () {
    $user = User::factory()->create(['password' => 'password']);

    loginAs($user)
        ->click('[data-menu="account"] [role="button"]')
        ->press('@Logout')
        ->assertPathIs('/login')
        ->navigate('/ideas/create')
        ->assertPathIs('/login')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');
