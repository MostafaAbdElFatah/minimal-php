<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Models\Idea;
use App\Models\User;
use App\View\Components\idea\IdeaCard;
use App\View\Components\idea\IdeaStatus;
use App\View\Components\idea\StatusFilter;

covers(IdeaCard::class, IdeaStatus::class, StatusFilter::class);

describe('idea status badge', function () {
    it('renders the state label with its color classes', function (IdeaState $state) {
        $idea = Idea::factory()->make(['state' => $state, 'user_id' => 1]);

        $view = $this->blade('<x-idea.idea-status :idea="$idea" />', ['idea' => $idea]);

        $view->assertSee($state->value)->assertSee("bg-{$state->color()}-500/10", false);
    })->with('idea states');
})->group('feature', 'components');

describe('idea card', function () {
    it('links to the idea and shows the escaped title and description', function () {
        $idea = Idea::factory()->create([
            'title' => 'Card <title>',
            'description' => 'Card description text',
            'state' => IdeaState::ACTIVE,
        ]);

        $view = $this->blade('<x-idea.idea-card :idea="$idea" />', ['idea' => $idea]);

        $view->assertSee(url("/ideas/{$idea->id}"), false)
            ->assertSee('Card &lt;title&gt;', false)
            ->assertDontSee('Card <title>', false)
            ->assertSee('Card description text')
            ->assertSee('active');
    });
})->group('feature', 'components');

describe('status filter', function () {
    it('lists every state with All states first and nothing selected by default', function () {
        $view = $this->blade('<x-idea.status-filter />');

        $view->assertSee('Filter by state')->assertSee('All states')->assertDontSee('selected', false)->assertDontSee('>Clear<', false);
        foreach (IdeaState::cases() as $state) {
            $view->assertSee('value="'.$state->value.'"', false);
        }
    });

    it('marks the current state as selected and offers a clear link', function () {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/?state=paused');

        $response->assertSee('value="paused"'."\n".'                    selected', false)->assertSee('Clear');
    });
})->group('feature', 'components');

describe('empty state', function () {
    it('renders the onboarding message when not filtered', function () {
        $this->blade('<x-idea.empty-state />')
            ->assertSee('No ideas yet')
            ->assertSee('+ Create Your First Idea')
            ->assertDontSee('No ideas found');
    });

    it('renders the filtered message with the escaped state when filtered', function () {
        $this->blade('<x-idea.empty-state :filtered="true" state="<b>x</b>" />')
            ->assertSee('No ideas found')
            ->assertSee('&lt;b&gt;x&lt;/b&gt;', false)
            ->assertSee('Clear Filter');
    });
})->group('feature', 'components');

describe('form fields', function () {
    it('pre-fill from the given value', function () {
        $this->withViewErrors([])->blade('<x-idea.title value="Given title" /><x-idea.description value="Given description" /><x-idea.state value="draft" />')
            ->assertSee('value="Given title"', false)
            ->assertSee('>Given description</textarea>', false)
            ->assertSee('value="draft"'."\n".'                class="bg-gray-800"'."\n".'                selected', false);
    });

    it('prefer old input over the given value and show the error', function () {
        withOldInput(['title' => 'Old title']);

        $this->withViewErrors([])
            ->blade('<x-idea.title value="Given title" />')
            ->assertSee('value="Old title"', false);

        $this->withViewErrors(['title' => 'The title field is required.'])
            ->blade('<x-idea.title />')
            ->assertSee('The title field is required.')
            ->assertSee('border-error');
    });
})->group('feature', 'components');
