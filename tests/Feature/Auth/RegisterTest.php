<?php

use App\Models\User;
use Tests\TestCase;

test('a guest can register with a unique email', function () {
    /** @var TestCase $this */
    $response = $this->post('/register', [
        'first_name' => 'Jane',
        'last_name' => 'Jon',
        'email' => 'jane@example.com',
        'password' => 'jane-password',
        'password_confirmation' => 'jane-password',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
    ]);
});

test('a guest cannot register with an existing email address', function () {
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
