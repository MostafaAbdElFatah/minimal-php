<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterUserController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

covers(RegisterUserController::class);

describe('registration page', function () {
    it('renders the registration form for guests', function () {
        $this->get(route('register'))
            ->assertOk()
            ->assertViewIs('auth.register')
            ->assertSee('Create your IdeaHub account');
    });

    it('redirects authenticated users away from the registration form', function () {
        $this->actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirect('/');
    });
})->group('feature', 'auth');

describe('creating an account', function () {
    it('stores a trimmed user with a hashed password and signs them in', function () {
        $response = $this->post(route('register'), [
            'first_name' => '  Jane  ',
            'last_name' => '  Doe  ',
            'email' => '  jane@example.com  ',
            'password' => ' jane-password ',
            'password_confirmation' => ' jane-password ',
        ]);

        $response->assertRedirect('/')->assertSessionHas('status', 'Account created successfully!');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);
        $user = User::firstWhere('email', 'jane@example.com');
        expect(Hash::check('jane-password', $user->password))->toBeTrue()
            ->and($user->email_verified_at)->toBeNull();
    });

    it('rejects an email that is already registered and creates nothing', function () {
        User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'password' => 'jane-password',
            'password_confirmation' => 'jane-password',
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email' => 'The email has already been taken.']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    });

    it('rejects an empty payload and creates nothing', function () {
        $response = $this->from(route('register'))->post(route('register'), []);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    });

    it('redirects an authenticated user without creating another account', function () {
        $this->actingAs(User::factory()->create());

        $this->post(route('register'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'password' => 'jane-password',
            'password_confirmation' => 'jane-password',
        ])->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    });
})->group('feature', 'auth');
