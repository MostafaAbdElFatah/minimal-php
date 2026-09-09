<?php

declare(strict_types=1);

use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;

covers(StoreRegisterRequest::class);

/**
 * @return array<string, string>
 */
function registrationPayload(array $overrides = []): array
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

it('rejects an email that already exists regardless of surrounding whitespace', function () {
    User::factory()->create(['email' => 'jane@example.com']);

    $this->from(route('register'))
        ->post(route('register'), registrationPayload(['email' => '  jane@example.com  ']))
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors(['email' => 'The email has already been taken.']);

    $this->assertDatabaseCount('users', 1);
})->group('feature', 'requests');

it('shows the user-facing message for each invalid field', function (array $overrides, string $field, string $message) {
    $this->from(route('register'))
        ->post(route('register'), registrationPayload($overrides))
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors([$field => $message]);

    $this->assertGuest();
})->with([
    'short first name' => [['first_name' => 'J'], 'first_name', 'The first name field must be at least 2 characters.'],
    'short last name' => [['last_name' => 'D'], 'last_name', 'The last name field must be at least 2 characters.'],
    'invalid email' => [['email' => 'not-an-email'], 'email', 'The email field must be a valid email address.'],
    'mismatched confirmation' => [['password_confirmation' => 'other-password'], 'password', 'The password field confirmation does not match.'],
])->group('feature', 'requests');

it('does not keep the password in old input after a failure', function () {
    $this->from(route('register'))
        ->post(route('register'), registrationPayload(['email' => 'bad']))
        ->assertSessionHasInput('first_name', 'Jane')
        ->assertSessionMissing('_old_input.password');
})->group('feature', 'requests');
