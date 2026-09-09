<?php

declare(strict_types=1);

use App\Models\Idea;
use App\View\Components\idea\IdeaCard;
use App\View\Components\idea\IdeaStatus;
use App\View\Components\idea\StatusFilter;
use App\View\Components\nav_bar;
use App\View\Components\theme;
use App\View\Components\UserMenu;
use Illuminate\Contracts\View\View;

covers(IdeaCard::class, IdeaStatus::class, StatusFilter::class, nav_bar::class, theme::class, UserMenu::class);

it('renders the matching Blade view', function (string $component, string $view, bool $needsIdea = false) {
    $rendered = ($needsIdea ? new $component(Idea::factory()->make(['user_id' => 1])) : new $component)->render();

    expect($rendered)->toBeInstanceOf(View::class)
        ->and($rendered->name())->toBe($view)
        ->and(view()->exists($view))->toBeTrue();
})->with([
    'idea card' => [IdeaCard::class, 'components.idea.idea-card', true],
    'idea status' => [IdeaStatus::class, 'components.idea.idea-status', true],
    'status filter' => [StatusFilter::class, 'components.idea.status-filter'],
    'nav bar' => [nav_bar::class, 'components.nav-bar'],
    'theme' => [theme::class, 'components.theme'],
    'user menu' => [UserMenu::class, 'components.user-menu'],
])->group('unit', 'components');

it('exposes the idea to the card and status views', function (string $component) {
    $idea = Idea::factory()->make(['title' => 'Exposed idea', 'user_id' => 1]);

    expect((new $component($idea))->idea)->toBe($idea);
})->with([IdeaCard::class, IdeaStatus::class])->group('unit', 'components');
