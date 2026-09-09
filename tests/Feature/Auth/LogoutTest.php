<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\SessionsController;
use App\Models\User;

covers(SessionsController::class);

it('logs the user out, invalidates the session and rotates the CSRF token', function () {
    $this->actingAs(User::factory()->create());
    session(['logout-marker' => 'present']);
    $sessionId = session()->getId();
    $csrfToken = session()->token();

    $response = $this->delete(route('logout'));

    $response->assertRedirect('/')->assertSessionHas('status', 'You have been logged out.');
    $this->assertGuest();
    expect(session()->has('logout-marker'))->toBeFalse()
        ->and(session()->getId())->not->toBe($sessionId)
        ->and(session()->token())->toBeString()->not->toBe($csrfToken);
})->group('feature', 'auth');

it('redirects guests to the login page instead of logging out', function () {
    $this->delete(route('logout'))->assertRedirect(route('login'));
})->group('feature', 'auth');
