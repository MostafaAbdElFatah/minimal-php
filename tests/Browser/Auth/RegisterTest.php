<?php

declare(strict_types=1);

use App\Models\User;

/**
 * @return array<string, string>
 */
function registrationForm(array $overrides = []): array
{
    return [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'jane-password',
        'password_confirmation' => 'jane-password',
        ...$overrides,
    ];
}

it('registers a new user and lands on their empty idea list', function () {
    $page = visit(route('register'))->assertNoJavaScriptErrors();
    foreach (registrationForm() as $field => $value) {
        $page->fill($field, $value);
    }

    $page->press('@register')
        ->assertPathIs('/')
        ->assertSee('No ideas yet')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'first_name' => 'Jane']);
})->group('browser', 'auth');

it('registers on a mobile viewport', function () {
    $page = visit(route('register'))->on()->mobile()->assertNoJavaScriptErrors();
    foreach (registrationForm() as $field => $value) {
        $page->fill($field, $value);
    }

    $page->press('@register')->assertPathIs('/')->assertNoJavaScriptErrors();

    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
})->group('browser', 'auth');

it('shows the server-side message for each invalid field and keeps the user on the form', function (array $overrides, string $message) {
    User::factory()->create(['email' => 'taken@example.com']);
    $page = visit(route('register'));
    $page->script('document.querySelector("main form").noValidate = true;');
    foreach (registrationForm($overrides) as $field => $value) {
        $page->fill($field, $value);
    }

    $page->press('@register')
        ->assertPathIs('/register')
        ->assertSee($message)
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
})->with([
    'taken email' => [['email' => 'taken@example.com'], 'The email has already been taken.'],
    'mismatched confirmation' => [['password_confirmation' => 'different-password'], 'The password field confirmation does not match.'],
    'missing first name' => [['first_name' => ''], 'The first name field is required.'],
    'missing last name' => [['last_name' => ''], 'The last name field is required.'],
    'missing email' => [['email' => ''], 'The email field is required.'],
    'invalid email' => [['email' => 'not-an-email'], 'The email field must be a valid email address.'],
    'missing password' => [['password' => '', 'password_confirmation' => ''], 'The password field is required.'],
    'short password' => [['password' => '123', 'password_confirmation' => '123'], 'The password field must be at least 8 characters.'],
])->group('browser', 'auth');

it('updates the password strength meter while typing', function () {
    visit(route('register'))
        ->fill('password', 'abc')
        ->assertSee('Very weak')
        ->fill('password', 'Str0ng-Passw0rd!')
        ->assertSee('Strong')
        ->assertNoJavaScriptErrors();
})->group('browser', 'auth');
