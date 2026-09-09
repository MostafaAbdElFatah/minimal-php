<?php

declare(strict_types=1);

use App\Enums\IdeaState;

covers(IdeaState::class);

test('every state maps to its badge color', function (IdeaState $state, string $color) {
    expect($state->color())->toBe($color);
})->with([
    'pending is yellow' => [IdeaState::PENDING, 'yellow'],
    'active is green' => [IdeaState::ACTIVE, 'green'],
    'complete is blue' => [IdeaState::COMPLETE, 'blue'],
    'incomplete is orange' => [IdeaState::INCOMPLETE, 'orange'],
    'draft is gray' => [IdeaState::DRAFT, 'gray'],
    'paused is amber' => [IdeaState::PAUSED, 'amber'],
    'cancelled is red' => [IdeaState::CANCELLED, 'red'],
    'archived is purple' => [IdeaState::ARCHIVED, 'purple'],
])->group('unit', 'models');

test('the stored values are the lowercase state names', function () {
    expect(array_map(fn (IdeaState $state): string => $state->value, IdeaState::cases()))
        ->toBe(['pending', 'active', 'complete', 'incomplete', 'draft', 'paused', 'cancelled', 'archived']);
})->group('unit', 'models');

test('an unknown stored value cannot be cast to a state', function () {
    expect(IdeaState::tryFrom('unknown'))->toBeNull();
})->group('unit', 'models');
