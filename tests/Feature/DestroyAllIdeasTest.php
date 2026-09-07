<?php

use App\Models\Idea;
use App\Models\User;
use Tests\TestCase;

test('it deletes all ideas after confirmation', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    Idea::factory()->count(3)->for($user)->create();

    $response = $this->actingAs($user)
        ->delete(route('ideas.destroy-all'));

    $response->assertRedirect('/');
    $response->assertSessionHas('status', 'All ideas deleted successfully!');
    $this->assertDatabaseCount('ideas', 0);
});
