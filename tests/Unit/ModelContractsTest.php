<?php

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

test('the idea model casts state and exposes its computed color', function () {
    $idea = Idea::factory()->make([
        'state' => IdeaState::ACTIVE,
    ]);

    expect($idea->state)->toBeInstanceOf(IdeaState::class);
    expect($idea->state)->toBe(IdeaState::ACTIVE);
    expect($idea->color)->toBe('green');
});
use Illuminate\Support\Carbon;

test('the user model exposes its ideas relationship and admin helper', function () {
    User::factory()->create();
    $user = User::factory()->create();

    expect($user->ideas())->toBeInstanceOf(HasMany::class);
    expect($user->isAdmin())->toBeFalse();
    expect(User::findOrFail(1)->isAdmin())->toBeTrue();
    expect(User::make(['id' => '1'])->isAdmin())->toBeFalse();
});

test('the user model casts email verification timestamps to Carbon', function () {
    $user = User::factory()->make(['email_verified_at' => '2026-01-01 12:00:00']);

    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
});

test('the idea factory creates an owning user by default', function () {
    /** @var TestCase $this */
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
    $this->assertModelExists($idea->user);
});
