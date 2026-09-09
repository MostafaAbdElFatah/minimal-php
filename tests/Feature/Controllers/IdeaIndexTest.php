<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

covers(IdeaController::class);

it('lists only the authenticated users ideas with their count', function () {
    $user = User::factory()->create();
    $mine = Idea::factory()->count(2)->for($user)->sequence(
        ['title' => 'First idea'],
        ['title' => 'Second idea'],
    )->create();
    Idea::factory()->create(['title' => 'Someone elses idea']);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk()
        ->assertViewIs('ideas.index')
        ->assertSee('(2)')
        ->assertSee('First idea')
        ->assertSee('Second idea')
        ->assertDontSee('Someone elses idea')
        ->assertViewHas('ideas', fn (LengthAwarePaginator $ideas): bool => $ideas->pluck('id')->sort()->values()->all() === $mine->pluck('id')->sort()->values()->all());
});

it('filters ideas by state', function () {
    $user = User::factory()->create();
    $active = Idea::factory()->for($user)->create(['state' => IdeaState::ACTIVE]);
    Idea::factory()->for($user)->create(['state' => IdeaState::PENDING]);

    $response = $this->actingAs($user)->get('/?state=active');

    $response->assertOk()
        ->assertSee('(1)')
        ->assertViewHas('ideas', fn (LengthAwarePaginator $ideas): bool => $ideas->count() === 1 && $ideas->first()->is($active));
});

it('shows the filtered empty state when no idea matches the filter', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->create(['state' => IdeaState::PENDING]);

    $this->actingAs($user)->get('/?state=archived')
        ->assertOk()
        ->assertSee('No ideas found')
        ->assertSee('There are no ideas with the')
        ->assertSee('archived')
        ->assertSee('Clear Filter');
});

it('returns no ideas for an unknown state instead of failing', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->create();

    $this->actingAs($user)->get('/?state=%27%20OR%201%3D1%20--')
        ->assertOk()
        ->assertSee('(0)')
        ->assertSee('No ideas found');
});

it('shows the onboarding empty state when the user has no ideas', function () {
    $this->actingAs(User::factory()->create())->get(route('home'))
        ->assertOk()
        ->assertSee('No ideas yet')
        ->assertSee('+ Create Your First Idea')
        ->assertDontSee('Delete All Ideas');
});

it('shows the delete all button only when ideas exist', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->create();

    $this->actingAs($user)->get(route('home'))->assertSee('Delete All Ideas');
});

it('paginates ten ideas per page', function (int $total, int $lastPage) {
    $user = User::factory()->create();
    Idea::factory()->count($total)->for($user)->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk()->assertViewHas('ideas', fn (LengthAwarePaginator $ideas): bool => $ideas->perPage() === 10
        && $ideas->total() === $total
        && $ideas->lastPage() === $lastPage);
})->with('idea page sizes');

it('renders pagination controls when there is more than one page', function () {
    $user = User::factory()->create();
    Idea::factory()->count(11)->for($user)->create();

    $this->actingAs($user)->get(route('home'))
        ->assertSee('aria-label="Pagination"', false)
        ->assertSee('aria-label="Next page"', false)
        ->assertSee('aria-current="page"', false);
});

it('escapes idea titles and descriptions in the list', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->create([
        'title' => '<b>bold</b> title',
        'description' => '<script>alert(1)</script> description',
    ]);

    $this->actingAs($user)->get(route('home'))
        ->assertDontSee('<b>bold</b>', false)
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;b&gt;bold&lt;/b&gt;', false);
});

it('redirects guests to the login page', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});
