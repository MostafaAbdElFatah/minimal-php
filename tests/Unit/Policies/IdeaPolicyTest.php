<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use App\Policies\IdeaPolicy;

covers(IdeaPolicy::class);

/**
 * Build an in-memory idea owned by the given user without touching the database.
 */
function ideaOwnedBy(User $owner): Idea
{
    return Idea::factory()->make(['user_id' => $owner->id])->setRelation('user', $owner);
}

test('nobody may list every idea', function () {
    $user = (new User)->forceFill(['id' => 1]);

    expect((new IdeaPolicy)->viewAny($user))->toBeFalse();
})->group('unit', 'policies');

test('the owner is allowed to view, update and delete their idea', function (string $ability) {
    $owner = User::factory()->make()->forceFill(['id' => 1]);
    $idea = ideaOwnedBy($owner);

    $response = (new IdeaPolicy)->{$ability}($owner, $idea);

    expect($response->allowed())->toBeTrue();
})->with(['view', 'update', 'delete'])->group('unit', 'policies');

test('another user is denied as not found so the idea is never revealed', function (string $ability) {
    $owner = User::factory()->make()->forceFill(['id' => 1]);
    $stranger = User::factory()->make()->forceFill(['id' => 2]);
    $idea = ideaOwnedBy($owner);

    $response = (new IdeaPolicy)->{$ability}($stranger, $idea);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
})->with(['view', 'update', 'delete'])->group('unit', 'policies');
