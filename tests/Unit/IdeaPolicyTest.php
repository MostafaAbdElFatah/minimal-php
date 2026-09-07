<?php

use App\Models\Idea;
use App\Models\User;
use App\Policies\IdeaPolicy;
use Illuminate\Auth\Access\Response;

test('the policy denies viewing any idea collection', function () {
    $policy = new IdeaPolicy;

    expect($policy->viewAny(User::factory()->make()))->toBeFalse();
});

test('the policy allows an owner to view, update, and delete their idea', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();
    $policy = new IdeaPolicy;

    expect($policy->view($user, $idea)->allowed())->toBeTrue();
    expect($policy->update($user, $idea)->allowed())->toBeTrue();
    expect($policy->delete($user, $idea)->allowed())->toBeTrue();
});

test('the policy hides another user idea as not found', function () {
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $otherUser = User::factory()->create();
    $policy = new IdeaPolicy;

    expect($policy->view($otherUser, $idea))->toBeInstanceOf(Response::class);
    expect($policy->view($otherUser, $idea)->denied())->toBeTrue();
    expect($policy->update($otherUser, $idea)->denied())->toBeTrue();
    expect($policy->delete($otherUser, $idea)->denied())->toBeTrue();
});
