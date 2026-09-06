<?php

use App\Models\Idea;
use App\Models\User;

test('an authenticated user owns a created idea', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/ideas/create', [
        'title' => 'A useful idea',
        'description' => 'A description that is long enough.',
        'state' => 'active',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('ideas', [
        'title' => 'A useful idea',
        'user_id' => $user->id,
    ]);
});

test('an authenticated user can filter their ideas by state', function () {
    $user = User::factory()->create();

    Idea::factory()->create([
        'user_id' => $user->id,
        'state' => 'active',
    ]);
    Idea::factory()->create([
        'user_id' => $user->id,
        'state' => 'pending',
    ]);

    $response = $this->actingAs($user)->get('/?state=active');

    $response->assertOk();
    $response->assertViewHas('ideas', function ($ideas) {
        return $ideas->count() === 1 && $ideas->first()->state->value === 'active';
    });
});
