<?php

use App\Models\Idea;
use App\Models\User;

test('it deletes all ideas after confirmation', function () {
    Idea::factory()->count(3)->create();

    $response = $this->actingAs(User::factory()->create())
        ->delete(route('ideas.destroy-all'));

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'All ideas deleted successfully!');
    $this->assertDatabaseCount('ideas', 0);
});
