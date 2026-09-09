<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

covers(User::class);

it('owns the ideas created through the relationship', function () {
    $user = User::factory()->create();
    Idea::factory()->count(2)->for($user)->create();
    Idea::factory()->create();

    expect($user->ideas)->toHaveCount(2)
        ->and($user->ideas->every(fn (Idea $idea): bool => $idea->user_id === $user->id))->toBeTrue();
})->group('feature', 'models');

it('eager loads ideas without lazy loading', function () {
    $user = User::factory()->create();
    Idea::factory()->count(2)->for($user)->create();

    $loaded = User::with('ideas')->findOrFail($user->id);

    expect($loaded->relationLoaded('ideas'))->toBeTrue()->and($loaded->ideas)->toHaveCount(2);
})->group('feature', 'models');

it('stores the password hashed', function () {
    $user = User::factory()->create(['password' => 'plain-password']);

    expect($user->getRawOriginal('password'))->not->toBe('plain-password')
        ->and(Hash::check('plain-password', $user->fresh()->password))->toBeTrue();
})->group('feature', 'models');

it('does not rehash an already hashed password', function () {
    $hash = Hash::make('plain-password');

    $user = User::factory()->create(['password' => $hash]);

    expect($user->getRawOriginal('password'))->toBe($hash);
})->group('feature', 'models');

it('deletes its ideas when the user is deleted', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();
    $otherIdea = Idea::factory()->create();

    $user->delete();

    $this->assertModelMissing($idea);
    $this->assertModelExists($otherIdea);
})->group('feature', 'models');

it('is an admin only when it is the first user', function () {
    $first = User::factory()->create();
    $second = User::factory()->create();

    expect($first->isAdmin())->toBeTrue()->and($second->isAdmin())->toBeFalse();
})->group('feature', 'models');
