<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;

covers(Idea::class);

it('persists a factory idea with an owner', function () {
    $idea = Idea::factory()->create();

    $this->assertModelExists($idea);
    $this->assertModelExists($idea->user);
    expect($idea->title)->not->toBeEmpty()
        ->and($idea->description)->not->toBeEmpty()
        ->and($idea->state)->toBeInstanceOf(IdeaState::class);
})->group('feature', 'models');

it('round-trips the state enum through the database', function (IdeaState $state) {
    $idea = Idea::factory()->create(['state' => $state]);

    $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'state' => $state->value]);
    expect($idea->fresh()->state)->toBe($state);
})->with('idea states')->group('feature', 'models');

it('defaults the state to pending when none is given', function () {
    $idea = Idea::query()->create([
        'title' => 'Untitled',
        'description' => 'No state supplied',
        'user_id' => User::factory()->create()->id,
    ]);

    expect($idea->fresh()->state)->toBe(IdeaState::PENDING);
})->group('feature', 'models');

it('resolves its owner through the relationship', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    expect($idea->user->is($user))->toBeTrue()
        ->and($user->ideas->first()->is($idea))->toBeTrue();
})->group('feature', 'models');

it('touches updated_at when edited', function () {
    $this->travelTo('2026-01-01 10:00:00');
    $idea = Idea::factory()->create();

    $this->travelTo('2026-01-02 10:00:00');
    $idea->update(['title' => 'Edited']);

    expect($idea->fresh()->updated_at->toDateTimeString())->toBe('2026-01-02 10:00:00')
        ->and($idea->fresh()->created_at->toDateTimeString())->toBe('2026-01-01 10:00:00');
})->group('feature', 'models');
