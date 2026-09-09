<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;

it('creates persistable users', function () {
    User::factory()->count(3)->create();

    $this->assertDatabaseCount('users', 3);
})->group('feature', 'database', 'models');

it('creates unverified users through the factory state', function () {
    $user = User::factory()->unverified()->create();

    $this->assertDatabaseHas('users', ['id' => $user->id, 'email_verified_at' => null]);
})->group('feature', 'database', 'models');

it('creates ideas with a fresh owner each', function () {
    Idea::factory()->count(3)->create();

    $this->assertDatabaseCount('ideas', 3);
    $this->assertDatabaseCount('users', 3);
})->group('feature', 'database', 'models');

it('creates ideas for a shared owner with for()', function () {
    $user = User::factory()->create();

    Idea::factory()->count(3)->for($user)->create();

    $this->assertDatabaseCount('users', 1);
    expect(Idea::where('user_id', $user->id)->count())->toBe(3);
})->group('feature', 'database', 'models');

it('only produces known idea states', function () {
    $states = Idea::factory()->count(20)->create()->pluck('state')->unique();

    expect($states->every(fn (IdeaState $state): bool => in_array($state, IdeaState::cases(), true)))->toBeTrue();
})->group('feature', 'database', 'models');
