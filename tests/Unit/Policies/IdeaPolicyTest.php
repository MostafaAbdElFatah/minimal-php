<?php

use App\Models\Idea;
use App\Models\User;
use App\Policies\IdeaPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

it('allows the owner to view, update, and delete an idea', function () {
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $policy = new IdeaPolicy;

    expect($policy->view($owner, $idea)->allowed())->toBeTrue()
        ->and($policy->update($owner, $idea)->allowed())->toBeTrue()
        ->and($policy->delete($owner, $idea)->allowed())->toBeTrue();
});

it('denies a non-owner from viewing, updating, or deleting an idea as not found', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $policy = new IdeaPolicy;

    expect($policy->view($otherUser, $idea))->toBeInstanceOf(Response::class)
        ->and($policy->view($otherUser, $idea)->denied())->toBeTrue()
        ->and($policy->update($otherUser, $idea)->denied())->toBeTrue()
        ->and($policy->delete($otherUser, $idea)->denied())->toBeTrue();
});

it('denies guests from viewing, updating, or deleting an idea through the gate', function () {
    $idea = Idea::factory()->create();

    expect(Gate::forUser(null)->allows('view', $idea))->toBeFalse()
        ->and(Gate::forUser(null)->allows('update', $idea))->toBeFalse()
        ->and(Gate::forUser(null)->allows('delete', $idea))->toBeFalse();
});
