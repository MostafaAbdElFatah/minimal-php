<?php

use App\Models\User;
use Tests\TestCase;

it('rejects an empty registration payload with validation errors', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', []);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password']);
});

it('stores a trimmed user and logs them in when registration is valid', function () {
    /** @var TestCase $this */
    $response = $this->post('/register', [
        'first_name' => '  Jane  ',
        'last_name' => '  Jon  ',
        'email' => '  jane@example.com  ',
        'password' => 'jane-password',
        'password_confirmation' => 'jane-password',
    ]);

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'Account created successfully!');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'first_name' => 'Jane',
        'last_name' => 'Jon',
        'email' => 'jane@example.com',
    ]);
});

it('trims the registration password and confirmation before authentication', function () {
    /** @var TestCase $this */
    $response = $this->post('/register', [
        'first_name' => 'Jane',
        'last_name' => 'Jon',
        'email' => 'trimmed-password@example.com',
        'password' => ' secret-password ',
        'password_confirmation' => ' secret-password ',
    ]);

    $response->assertRedirect('/');
    $this->delete('/logout');

    $this->post('/login', [
        'email' => 'trimmed-password@example.com',
        'password' => 'secret-password',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});

it('rejects an existing email address', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'jane@example.com',
    ]);

    $response = $this->from('/register')->post('/register', [
        'first_name' => 'Jane',
        'last_name' => 'Jon',
        'email' => 'jane@example.com',
        'password' => 'jane-password',
        'password_confirmation' => 'jane-password',
    ]);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
    $this->assertDatabaseCount('users', 1);
});
