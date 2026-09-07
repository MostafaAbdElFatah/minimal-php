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

test('an authenticated user can see the number of matching ideas', function () {
    $user = User::factory()->create();

    Idea::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('(2)');
});

test('an authenticated user sees the default empty state at the bottom of the browser', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('No ideas yet');
    $response->assertSee('fixed bottom-4');
});

test('an authenticated user sees the filtered empty state', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/?state=active');

    $response->assertOk();
    $response->assertSee('No ideas found');
    $response->assertSee('There are no ideas with the');
    $response->assertSee('active');
});

test('an authenticated user sees the styled pagination controls', function () {
    $user = User::factory()->create();

    Idea::factory()->count(11)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('aria-label="Pagination"', false);
    $response->assertSee('aria-label="Next page"', false);
    $response->assertSee('aria-current="page"', false);
});
