<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

covers(Idea::class);

it('casts the stored state string to the enum', function () {
    $idea = new Idea(['state' => 'paused']);

    expect($idea->state)->toBe(IdeaState::PAUSED);
})->group('unit', 'models');

it('derives its color from the state', function (IdeaState $state) {
    $idea = Idea::factory()->make(['state' => $state, 'user_id' => 1]);

    expect($idea->color)->toBe($state->color());
})->with('idea states')->group('unit', 'models');

it('serializes the state as its stored value', function () {
    $idea = Idea::factory()->make(['state' => IdeaState::ACTIVE, 'user_id' => 1]);

    expect($idea->toArray()['state'])->toBe('active');
})->group('unit', 'models');

it('allows mass assignment of the editable attributes only', function () {
    $idea = new Idea([
        'title' => 'A title',
        'description' => 'A description',
        'state' => 'draft',
        'user_id' => 7,
    ]);

    expect($idea->getAttributes())->toBe([
        'title' => 'A title',
        'description' => 'A description',
        'state' => 'draft',
        'user_id' => 7,
    ]);
})->group('unit', 'models');

it('rejects mass assignment of the primary key', function () {
    expect(fn () => new Idea(['id' => 99, 'title' => 'x']))
        ->toThrow(MassAssignmentException::class);
})->group('unit', 'models');

it('belongs to a user through the user_id foreign key', function () {
    $relation = (new Idea)->user();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getForeignKeyName())->toBe('user_id')
        ->and($relation->getRelated())->toBeInstanceOf(User::class);
})->group('unit', 'models');
