<?php

use App\Models\Idea;

test('it deletes all ideas after confirmation', function () {
    Idea::factory()->count(3)->create();

    $response = $this->delete(route('ideas.destroy-all'));

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'All ideas deleted successfully!');
    $this->assertDatabaseCount('ideas', 0);
});
