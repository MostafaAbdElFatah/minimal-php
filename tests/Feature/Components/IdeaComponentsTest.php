<?php

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use App\View\Components\idea\IdeaCard;
use App\View\Components\idea\IdeaStatus;
use App\View\Components\idea\StatusFilter;
use App\View\Components\nav_bar as NavBarComponent;
use App\View\Components\theme as ThemeComponent;
use Illuminate\Contracts\View\View;
use Tests\TestCase;

it('maps the idea component classes to their views', function () {
    expect((new IdeaStatus)->render())->toBeInstanceOf(View::class)
        ->and((new StatusFilter)->render())->toBeInstanceOf(View::class)
        ->and((new IdeaCard)->render())->toBeInstanceOf(View::class)
        ->and((new NavBarComponent)->render())->toBeInstanceOf(View::class)
        ->and((new ThemeComponent)->render())->toBeInstanceOf(View::class);
});

it('renders the authenticated idea components on the home page', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create([
        'title' => 'A visible idea',
        'description' => 'A description shown on the idea card.',
        'state' => IdeaState::ACTIVE,
    ]);

    $response = $this->actingAs($user)->get('/?state=active');

    $response->assertOk()
        ->assertSee('Filter by state')
        ->assertSee('value="active"', false)
        ->assertSee('selected', false)
        ->assertSee('Clear')
        ->assertSee('A visible idea')
        ->assertSee('A description shown on the idea card.')
        ->assertSee("/ideas/{$idea->id}", false)
        ->assertSee('active')
        ->assertSee('IdeaHub')
        ->assertSee('Theme')
        ->assertSee('Light')
        ->assertSee('Dark');
});
