<?php

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

test('idea casts state to its enum and derives its color', function () {
    $idea = Idea::factory()->make(['state' => IdeaState::PAUSED]);

    expect($idea->state)->toBe(IdeaState::PAUSED)
        ->and($idea->color)->toBe('amber');
})->group('unit');

test('idea belongs to its owner', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    expect($idea->user())->toBeInstanceOf(BelongsTo::class)
        ->and($idea->user->is($user))->toBeTrue();
})->group('unit');

test('idea factory creates every persistable attribute', function () {
    /** @var TestCase $this */
    $idea = Idea::factory()->create();

    $this->assertModelExists($idea);
    expect($idea->title)->not->toBeEmpty()
        ->and($idea->description)->not->toBeEmpty()
        ->and($idea->state)->toBeInstanceOf(IdeaState::class);
})->group('unit');
