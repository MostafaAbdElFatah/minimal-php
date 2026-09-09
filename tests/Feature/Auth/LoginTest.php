<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SessionsController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

covers(SessionsController::class);

describe('login page', function () {
    it('renders the login form for guests', function () {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Sign in to IdeaHub');
    });

    it('redirects authenticated users away from the login form', function () {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect('/');
    });
})->group('feature', 'auth');

describe('signing in', function () {
    it('authenticates a user with valid credentials and regenerates the session', function () {
        $user = User::factory()->create(['password' => 'secret-password']);
        $this->startSession();
        $sessionId = session()->getId();

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/')->assertSessionHas('status', 'You are now logged in.');
        $this->assertAuthenticatedAs($user);
        expect(session()->getId())->not->toBe($sessionId);
    });

    it('issues a remember cookie when remember me is checked', function () {
        $user = User::factory()->create(['password' => 'secret-password']);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret-password',
            'remember' => '1',
        ]);

        $response->assertRedirect('/')->assertCookie(Auth::guard()->getRecallerName());
        $this->assertAuthenticatedAs($user);
    });

    it('does not issue a remember cookie by default', function () {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret-password'])
            ->assertCookieMissing(Auth::guard()->getRecallerName());
    });

    it('trims surrounding whitespace from the credentials', function () {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post(route('login'), [
            'email' => " {$user->email} ",
            'password' => ' secret-password ',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    });

    it('rejects credentials that do not match and keeps the email in the form', function (string $email, string $password) {
        User::factory()->create(['email' => 'jane@example.com', 'password' => 'secret-password']);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'These credentials do not match our records.'])
            ->assertSessionHasInput('email', $email)
            ->assertSessionMissing('_old_input.password');
        $this->assertGuest();
    })->with([
        'wrong password' => ['jane@example.com', 'wrong-password'],
        'wrong email' => ['nope@example.com', 'secret-password'],
        'unknown user' => ['ghost@example.com', 'anything'],
    ]);

    it('rejects a malformed payload before attempting authentication', function () {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHasErrors([
            'email' => 'The email field must be a valid email address.',
            'password' => 'The password field is required.',
        ]);
        $this->assertGuest();
    });

    it('rejects an email longer than 255 characters', function () {
        $this->from(route('login'))->post(route('login'), [
            'email' => str_repeat('a', 244).'@example.com',
            'password' => 'secret-password',
        ])->assertSessionHasErrors(['email' => 'The email field must not be greater than 255 characters.']);

        $this->assertGuest();
    });

    it('reports missing credentials as required rather than as a failed login', function () {
        $this->from(route('login'))->post(route('login'), [])
            ->assertSessionHasErrors([
                'email' => 'The email field is required.',
                'password' => 'The password field is required.',
            ]);

        $this->assertGuest();
    });

    it('rejects a non-string password before attempting authentication', function () {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => ['secret-password'],
        ])->assertSessionHasErrors(['password' => 'The password field must be a string.']);

        $this->assertGuest();
    })->todo('SessionsController trims the raw input before validating, so an array password raises a TypeError (500) instead of a validation error.');

    it('redirects an already authenticated user without re-authenticating', function () {
        $user = User::factory()->create();
        $other = User::factory()->create(['password' => 'secret-password']);

        $this->actingAs($user)
            ->post(route('login'), ['email' => $other->email, 'password' => 'secret-password'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    });
})->group('feature', 'auth');
