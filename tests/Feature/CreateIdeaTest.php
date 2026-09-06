<?php

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
