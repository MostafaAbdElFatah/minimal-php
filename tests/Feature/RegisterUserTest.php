<?php

use App\Models\User;

test('a guest can register with a unique email', function () {
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
