<?php

declare(strict_types=1);

use App\Enums\IdeaState;

dataset('idea states', array_combine(
    array_map(fn (IdeaState $state): string => $state->value, IdeaState::cases()),
    array_map(fn (IdeaState $state): array => [$state], IdeaState::cases()),
));

dataset('invalid idea payloads', [
    'missing title' => [['title' => ''], 'title', 'The title field is required.'],
    'title shorter than 3 characters' => [['title' => 'ab'], 'title', 'The title field must be at least 3 characters.'],
    'title longer than 255 characters' => [['title' => str_repeat('a', 256)], 'title', 'The title field must not be greater than 255 characters.'],
    'missing description' => [['description' => ''], 'description', 'The description field is required.'],
    'description shorter than 10 characters' => [['description' => 'too short'], 'description', 'The description field must be at least 10 characters.'],
    'missing state' => [['state' => ''], 'state', 'The state field is required.'],
    'unknown state' => [['state' => 'unknown'], 'state', 'The selected state is invalid.'],
]);
