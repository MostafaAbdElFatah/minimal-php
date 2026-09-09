<?php

use App\Enums\IdeaState;
use App\Models\User;
use Tests\TestCase;

it('rejects an empty idea payload with validation errors', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())
        ->from('/ideas/create')
        ->post('/ideas/create', []);

    $response->assertRedirect('/ideas/create');
    $response->assertSessionHasErrors(['title', 'description', 'state']);
});

it('rejects an idea title over the maximum length', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->from('/ideas/create')->post('/ideas/create', [
        'title' => str_repeat('a', 256),
        'description' => 'A valid description here.',
        'state' => IdeaState::ACTIVE->value,
    ]);

    $response->assertRedirect('/ideas/create')->assertSessionHasErrors('title');
})->group('feature');

it('stores a trimmed idea for the authenticated user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/ideas/create', [
        'title' => '  A useful idea  ',
        'description' => '  A description that is long enough.  ',
        'state' => '  '.IdeaState::ACTIVE->value.'  ',
    ]);

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'Idea submitted successfully!');

    $this->assertDatabaseHas('ideas', [
        'title' => 'A useful idea',
        'description' => 'A description that is long enough.',
        'state' => IdeaState::ACTIVE->value,
        'user_id' => $user->id,
    ]);
});
