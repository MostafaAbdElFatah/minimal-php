<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;

covers(IdeaController::class);

/**
 * @return array<string, string>
 */
function validIdeaPayload(array $overrides = []): array
{
    return [
        'title' => 'A useful idea',
        'description' => 'A description that is long enough.',
        'state' => IdeaState::ACTIVE->value,
        ...$overrides,
    ];
}

describe('create', function () {
    it('renders the new idea form', function () {
        $this->actingAs(User::factory()->create())
            ->get('/ideas/create')
            ->assertOk()
            ->assertViewIs('ideas.create')
            ->assertSee('New Idea');
    });

    it('redirects guests to the login page', function () {
        $this->get('/ideas/create')->assertRedirect(route('login'));
    });
})->group('feature', 'controllers');

describe('store', function () {
    it('creates an idea owned by the authenticated user', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/ideas/create', validIdeaPayload());

        $response->assertRedirect('/')->assertSessionHas('status', 'Idea submitted successfully!');
        $this->assertDatabaseHas('ideas', [...validIdeaPayload(), 'user_id' => $user->id]);
        $this->assertDatabaseCount('ideas', 1);
    });

    it('ignores a user_id supplied in the payload', function () {
        $user = User::factory()->create();
        $victim = User::factory()->create();

        $this->actingAs($user)->post('/ideas/create', validIdeaPayload(['user_id' => $victim->id]));

        $this->assertDatabaseHas('ideas', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('ideas', ['user_id' => $victim->id]);
    });

    it('stores nothing when validation fails', function () {
        $this->actingAs(User::factory()->create())
            ->from('/ideas/create')
            ->post('/ideas/create', validIdeaPayload(['title' => '']))
            ->assertRedirect('/ideas/create')
            ->assertSessionHasErrors('title');

        $this->assertDatabaseCount('ideas', 0);
    });

    it('redirects guests to the login page without storing anything', function () {
        $this->post('/ideas/create', validIdeaPayload())->assertRedirect(route('login'));

        $this->assertDatabaseCount('ideas', 0);
    });
})->group('feature', 'controllers');

describe('show', function () {
    it('renders an idea for its owner', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create(['title' => 'My idea', 'description' => 'A long description here.']);

        $this->actingAs($user)->get("/ideas/{$idea->id}")
            ->assertOk()
            ->assertViewIs('ideas.show')
            ->assertViewHas('idea', fn (Idea $viewIdea): bool => $viewIdea->is($idea))
            ->assertSee('My idea')
            ->assertSee('A long description here.');
    });

    it('escapes user supplied content', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create([
            'title' => '<script>alert("title")</script>',
            'description' => '<img src=x onerror=alert(1)> long description',
        ]);

        $response = $this->actingAs($user)->get("/ideas/{$idea->id}");

        $response->assertOk()
            ->assertDontSee('<script>alert("title")</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee('&lt;img src=x onerror=alert(1)&gt; long description', false);
    });

    it('returns 404 for another users idea so its existence is not revealed', function () {
        $idea = Idea::factory()->create();

        $this->actingAs(User::factory()->create())->get("/ideas/{$idea->id}")->assertNotFound();
    });

    it('returns 404 for a missing idea', function () {
        $this->actingAs(User::factory()->create())->get('/ideas/999999')->assertNotFound();
    });

    it('redirects guests to the login page', function () {
        $idea = Idea::factory()->create();

        $this->get("/ideas/{$idea->id}")->assertRedirect(route('login'));
    });
})->group('feature', 'controllers');

describe('edit', function () {
    it('renders the edit form with the current values for the owner', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create(['title' => 'Idea to edit', 'state' => IdeaState::PAUSED]);

        $this->actingAs($user)->get("/ideas/{$idea->id}/edit")
            ->assertOk()
            ->assertViewIs('ideas.edit')
            ->assertViewHas('idea', fn (Idea $viewIdea): bool => $viewIdea->is($idea))
            ->assertSee('value="Idea to edit"', false)
            ->assertSee('value="paused"'."\n".'                class="bg-gray-800"'."\n".'                selected', false);
    });

    it('returns 404 for another users idea', function () {
        $idea = Idea::factory()->create();

        $this->actingAs(User::factory()->create())->get("/ideas/{$idea->id}/edit")->assertNotFound();
    });

    it('redirects guests to the login page', function () {
        $idea = Idea::factory()->create();

        $this->get("/ideas/{$idea->id}/edit")->assertRedirect(route('login'));
    });
})->group('feature', 'controllers');

describe('update', function () {
    it('updates an owned idea and redirects to it', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create(['state' => IdeaState::PENDING]);

        $response = $this->actingAs($user)->put("/ideas/{$idea->id}", validIdeaPayload([
            'title' => 'New title',
            'state' => IdeaState::COMPLETE->value,
        ]));

        $response->assertRedirect("/ideas/{$idea->id}")->assertSessionHas('status', 'Idea updated successfully!');
        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'title' => 'New title',
            'description' => 'A description that is long enough.',
            'state' => 'complete',
            'user_id' => $user->id,
        ]);
    });

    it('does not let the owner reassign the idea to another user', function () {
        $user = User::factory()->create();
        $victim = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        $this->actingAs($user)->put("/ideas/{$idea->id}", validIdeaPayload(['user_id' => $victim->id]));

        expect($idea->fresh()->user_id)->toBe($user->id);
    });

    it('changes nothing when validation fails', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create(['title' => 'Original title']);

        $this->actingAs($user)
            ->from("/ideas/{$idea->id}/edit")
            ->put("/ideas/{$idea->id}", validIdeaPayload(['description' => 'short']))
            ->assertRedirect("/ideas/{$idea->id}/edit")
            ->assertSessionHasErrors('description');

        expect($idea->fresh()->title)->toBe('Original title');
    });

    it('returns 404 for another users idea and changes nothing', function () {
        $idea = Idea::factory()->create(['title' => 'Original title']);

        $this->actingAs(User::factory()->create())
            ->put("/ideas/{$idea->id}", validIdeaPayload(['title' => 'Hijacked']))
            ->assertNotFound();

        expect($idea->fresh()->title)->toBe('Original title');
    });

    it('redirects guests to the login page', function () {
        $idea = Idea::factory()->create();

        $this->put("/ideas/{$idea->id}", validIdeaPayload())->assertRedirect(route('login'));
    });
})->group('feature', 'controllers');

describe('destroy', function () {
    it('deletes an owned idea and redirects home', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete("/ideas/{$idea->id}");

        $response->assertRedirect('/')->assertSessionHas('status', 'Idea deleted successfully!');
        $this->assertModelMissing($idea);
    });

    it('returns 404 for another users idea and keeps it', function () {
        $idea = Idea::factory()->create();

        $this->actingAs(User::factory()->create())->delete("/ideas/{$idea->id}")->assertNotFound();

        $this->assertModelExists($idea);
    });

    it('redirects guests to the login page and keeps the idea', function () {
        $idea = Idea::factory()->create();

        $this->delete("/ideas/{$idea->id}")->assertRedirect(route('login'));

        $this->assertModelExists($idea);
    });
})->group('feature', 'controllers');
