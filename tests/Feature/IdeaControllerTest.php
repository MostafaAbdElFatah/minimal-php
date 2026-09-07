<?php

use App\Enums\IdeaState;
use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

covers('App\\Http\\Controllers\\IdeaController');

it('returns the authenticated users ideas with pagination and state filtering', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Idea::factory()->count(11)->for($user)->create(['state' => IdeaState::ACTIVE]);
    $pendingIdea = Idea::factory()->for($user)->create(['state' => IdeaState::PENDING]);
    $otherIdea = Idea::factory()->for($otherUser)->create(['state' => IdeaState::ACTIVE]);

    $response = $this->actingAs($user)->get('/?state=active');

    $response->assertOk()
        ->assertViewIs('ideas.index')
        ->assertViewHas('ideas', function ($ideas) use ($pendingIdea, $otherIdea): bool {
            return $ideas->total() === 11
                && $ideas->perPage() === 10
                && $ideas->count() === 10
                && $ideas->contains($pendingIdea) === false
                && $ideas->contains($otherIdea) === false
                && $ideas->every(fn (Idea $idea): bool => $idea->user_id !== $otherIdea->user_id
                    && $idea->state === IdeaState::ACTIVE);
        });
});

it('renders an idea for its owner', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create([
        'state' => IdeaState::ACTIVE,
    ]);

    $response = $this->actingAs($user)->get("/ideas/{$idea->id}");

    $response->assertOk()
        ->assertViewIs('ideas.show')
        ->assertViewHas('idea', fn (Idea $viewIdea): bool => $viewIdea->id === $idea->id)
        ->assertSee($idea->title);
});

it('returns 404 when a user views another user idea', function () {
    /** @var TestCase $this */
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get("/ideas/{$idea->id}");

    $response->assertNotFound();
});

it('renders the edit form for the idea owner', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Idea to edit',
        'state' => IdeaState::ACTIVE,
    ]);

    $response = $this->actingAs($user)->get("/ideas/{$idea->id}/edit");

    $response->assertOk()
        ->assertViewIs('ideas.edit')
        ->assertViewHas('idea', fn (Idea $viewIdea): bool => $viewIdea->id === $idea->id)
        ->assertSee('Idea to edit');
});

it('returns not found when a non-owner edits an idea', function () {
    /** @var TestCase $this */
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();

    $response = $this->actingAs(User::factory()->create())->get("/ideas/{$idea->id}/edit");

    $response->assertNotFound();
});

it('authorizes the edit action inside the controller', function () {
    /** @var TestCase $this */
    $owner = User::factory()->create();
    $idea = Idea::factory()->for($owner)->create();
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser);

    expect(fn () => app(IdeaController::class)->edit($idea))
        ->toThrow(AuthorizationException::class);
});

it('updates an owned idea and redirects to the idea page', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Old title',
        'description' => 'Old description that is long enough.',
        'state' => IdeaState::PENDING,
    ]);

    $response = $this->actingAs($user)->put("/ideas/{$idea->id}", [
        'title' => 'New title',
        'description' => 'A new description that is long enough.',
        'state' => IdeaState::ACTIVE->value,
    ]);

    $response->assertRedirect("/ideas/{$idea->id}");
    $response->assertSessionHas('status', 'Idea updated successfully!');

    $this->assertDatabaseHas('ideas', [
        'id' => $idea->id,
        'title' => 'New title',
        'description' => 'A new description that is long enough.',
        'state' => IdeaState::ACTIVE->value,
    ]);
});

it('deletes an owned idea and redirects home', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete("/ideas/{$idea->id}");

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'Idea deleted successfully!');
    $this->assertDatabaseMissing('ideas', ['id' => $idea->id]);
});
