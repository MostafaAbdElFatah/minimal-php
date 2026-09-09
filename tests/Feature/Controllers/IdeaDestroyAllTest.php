<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;

covers(IdeaController::class);

it('deletes every idea of the authenticated user and nobody elses', function () {
    $user = User::factory()->create();
    Idea::factory()->count(3)->for($user)->create();
    $otherIdea = Idea::factory()->create();

    $response = $this->actingAs($user)->delete(route('ideas.destroy-all'));

    $response->assertRedirect('/')->assertSessionHas('status', 'All ideas deleted successfully!');
    $this->assertDatabaseCount('ideas', 1);
    $this->assertModelExists($otherIdea);
})->group('feature', 'controllers');

it('succeeds when the user has no ideas', function () {
    $this->actingAs(User::factory()->create())
        ->delete(route('ideas.destroy-all'))
        ->assertRedirect('/')
        ->assertSessionHas('status', 'All ideas deleted successfully!');
})->group('feature', 'controllers');

it('redirects guests to the login page and deletes nothing', function () {
    Idea::factory()->count(2)->create();

    $this->delete(route('ideas.destroy-all'))->assertRedirect(route('login'));

    $this->assertDatabaseCount('ideas', 2);
})->group('feature', 'controllers');
