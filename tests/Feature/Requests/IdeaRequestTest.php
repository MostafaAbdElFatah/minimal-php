<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Http\Requests\IdeaRequest;
use App\Models\Idea;
use App\Models\User;

covers(IdeaRequest::class);

/**
 * The value matrix lives in tests/Unit/Requests/IdeaRequestTest.php. These tests
 * prove the routes apply the request and the user receives the message.
 */
it('rejects an empty payload on store with every required field', function () {
    $this->actingAs(User::factory()->create())
        ->from('/ideas/create')
        ->post('/ideas/create', [])
        ->assertRedirect('/ideas/create')
        ->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'description' => 'The description field is required.',
            'state' => 'The state field is required.',
        ]);
})->group('feature', 'requests');

it('applies the same rules on update', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $this->actingAs($user)
        ->from("/ideas/{$idea->id}/edit")
        ->put("/ideas/{$idea->id}", ['title' => 'ok', 'description' => 'long enough text', 'state' => 'unknown'])
        ->assertRedirect("/ideas/{$idea->id}/edit")
        ->assertSessionHasErrors([
            'title' => 'The title field must be at least 3 characters.',
            'state' => 'The selected state is invalid.',
        ]);
})->group('feature', 'requests');

it('trims whitespace before validating and storing', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/ideas/create', [
        'title' => '  A useful idea  ',
        'description' => '  A description that is long enough.  ',
        'state' => '  '.IdeaState::ACTIVE->value.'  ',
    ])->assertRedirect('/')->assertSessionHasNoErrors();

    $this->assertDatabaseHas('ideas', [
        'title' => 'A useful idea',
        'description' => 'A description that is long enough.',
        'state' => 'active',
        'user_id' => $user->id,
    ]);
})->group('feature', 'requests');

it('treats a whitespace-only title as missing', function () {
    $this->actingAs(User::factory()->create())
        ->from('/ideas/create')
        ->post('/ideas/create', ['title' => '   ', 'description' => 'A description that is long enough.', 'state' => 'active'])
        ->assertSessionHasErrors(['title' => 'The title field is required.']);
})->group('feature', 'requests');

it('re-populates the form with the previous input after a failure', function () {
    $this->actingAs(User::factory()->create())
        ->from('/ideas/create')
        ->post('/ideas/create', ['title' => 'Kept title', 'description' => 'short', 'state' => 'draft'])
        ->assertSessionHasInput('title', 'Kept title')
        ->assertSessionHasInput('state', 'draft');
})->group('feature', 'requests');
