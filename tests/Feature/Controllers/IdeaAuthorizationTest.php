<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;

covers(IdeaController::class);

/**
 * Every idea endpoint must hide another user's idea as 404 and send guests to login.
 * The full permission matrix lives in the policy tests; this proves the wiring.
 */
dataset('idea endpoints', [
    'show' => ['get', '/ideas/{idea}', []],
    'edit' => ['get', '/ideas/{idea}/edit', []],
    'update' => ['put', '/ideas/{idea}', ['title' => 'Hijacked', 'description' => 'A long enough description.', 'state' => 'active']],
    'destroy' => ['delete', '/ideas/{idea}', []],
]);

it('hides another users idea as 404', function (string $method, string $uri, array $payload) {
    $idea = Idea::factory()->create(['title' => 'Original']);

    $this->actingAs(User::factory()->create())
        ->{$method}(str_replace('{idea}', (string) $idea->id, $uri), $payload)
        ->assertNotFound();

    $this->assertModelExists($idea);
    expect($idea->fresh()->title)->toBe('Original');
})->with('idea endpoints')->group('feature', 'controllers', 'policies');

it('redirects guests to the login page', function (string $method, string $uri, array $payload) {
    $idea = Idea::factory()->create();

    $this->{$method}(str_replace('{idea}', (string) $idea->id, $uri), $payload)
        ->assertRedirect(route('login'));
})->with('idea endpoints')->group('feature', 'controllers', 'auth');
