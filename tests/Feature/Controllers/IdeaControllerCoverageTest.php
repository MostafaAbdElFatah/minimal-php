<?php

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Tests\TestCase;

covers('App\\Http\\Controllers\\IdeaController');

it('renders the create idea form for an authenticated user', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->get('/ideas/create');

    $response->assertOk()->assertViewIs('ideas.create');
})->group('feature');

it('returns not found for a missing idea binding', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->get('/ideas/999999');

    $response->assertNotFound();
})->group('feature');

it('returns not found when a non-owner updates an idea', function () {
    /** @var TestCase $this */
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();

    $response = $this->actingAs(User::factory()->create())->put("/ideas/{$idea->id}", [
        'title' => 'Changed title',
        'description' => 'A changed description that is long enough.',
        'state' => IdeaState::ACTIVE->value,
    ]);

    $response->assertNotFound();
    expect($idea->fresh()->title)->toBe($idea->title);
})->group('feature');

it('returns not found when a non-owner deletes an idea', function () {
    /** @var TestCase $this */
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();

    $response = $this->actingAs(User::factory()->create())->delete("/ideas/{$idea->id}");

    $response->assertNotFound();
    $this->assertModelExists($idea);
})->group('feature');

it('deletes only the authenticated users ideas in bulk', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Idea::factory()->count(2)->for($user)->create();
    $otherIdea = Idea::factory()->for($otherUser)->create();

    $response = $this->actingAs($user)->delete(route('ideas.destroy-all'));

    $response->assertRedirect('/');
    expect(Idea::whereBelongsTo($user)->count())->toBe(0);
    $this->assertModelExists($otherIdea);
})->group('feature');
