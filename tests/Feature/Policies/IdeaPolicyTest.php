<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use App\Policies\IdeaPolicy;
use Illuminate\Support\Facades\Gate;

covers(IdeaPolicy::class);

test('the policy is discovered for the Idea model', function () {
    expect(Gate::getPolicyFor(Idea::class))->toBeInstanceOf(IdeaPolicy::class);
})->group('feature', 'policies');

test('the gate resolves each ability for each actor', function (string $actor, string $ability, bool $allowed) {
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $user = match ($actor) {
        'owner' => $owner,
        'other user' => User::factory()->create(),
        'guest' => null,
    };

    expect(Gate::forUser($user)->allows($ability, $idea))->toBe($allowed);
})->with(function (): Generator {
    foreach (['view', 'update', 'delete'] as $ability) {
        yield "owner can {$ability}" => ['owner', $ability, true];
        yield "other user cannot {$ability}" => ['other user', $ability, false];
        yield "guest cannot {$ability}" => ['guest', $ability, false];
    }
    yield 'owner cannot viewAny' => ['owner', 'viewAny', false];
})->group('feature', 'policies');

test('a denied ability responds as not found', function () {
    $idea = Idea::factory()->create();

    $response = Gate::forUser(User::factory()->create())->inspect('view', $idea);

    expect($response->denied())->toBeTrue()->and($response->status())->toBe(404);
})->group('feature', 'policies');
