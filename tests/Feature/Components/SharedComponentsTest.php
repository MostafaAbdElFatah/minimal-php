<?php

declare(strict_types=1);

use App\Models\User;
use App\View\Components\nav_bar;
use App\View\Components\theme;
use App\View\Components\UserMenu;

covers(nav_bar::class, theme::class, UserMenu::class);

describe('error', function () {
    it('renders nothing without an error and the message with one', function () {
        $this->withViewErrors([])->blade('<x-error name="email" />')->assertDontSee('text-error', false);

        $this->withViewErrors(['email' => 'The email is wrong.'])
            ->blade('<x-error name="email" />')
            ->assertSee('The email is wrong.');
    });
})->group('feature', 'components');

describe('status message', function () {
    it('shows the flashed status and hides when there is none', function () {
        $this->blade('<x-status-message />')->assertDontSee('status-message', false);

        $this->withSession(['status' => 'Saved <ok>'])
            ->blade('<x-status-message />')
            ->assertSee('Saved &lt;ok&gt;', false)
            ->assertDontSee('Saved <ok>', false);
    });
})->group('feature', 'components');

describe('card', function () {
    it('renders header, slot and merged attributes', function () {
        $this->blade('<x-card class="extra" header="Head" bgColor="#fff">Body text</x-card>')
            ->assertSee('Head')
            ->assertSee('Body text')
            ->assertSee('class="card extra"', false)
            ->assertSee('background-color: #fff', false);
    });
})->group('feature', 'components');

describe('layout', function () {
    it('sets the title and shows the navigation by default', function () {
        $this->blade('<x-layout title="Page title">Content</x-layout>')
            ->assertSee('<title>Page title</title>', false)
            ->assertSee('navbar')
            ->assertSee('Content');
    });

    it('hides the navigation when asked', function () {
        $this->blade('<x-layout :show-nav="false">Content</x-layout>')
            ->assertDontSee('navbar')
            ->assertSee('Content');
    });
})->group('feature', 'components');

describe('auth inputs', function () {
    it('render labelled fields with old values and errors', function () {
        withOldInput(['email' => 'old@example.com', 'first_name' => 'Old']);

        $this->withViewErrors(['email' => 'The email is wrong.'])
            ->blade('<x-auth.email /><x-auth.name name="first_name" label="First name" /><x-auth.password :show-strength="true" />')
            ->assertSee('value="old@example.com"', false)
            ->assertSee('value="Old"', false)
            ->assertSee('The email is wrong.')
            ->assertSee('data-password-strength="password"', false)
            ->assertSee('data-password-toggle="password"', false);
    });

    it('echoes old password input, so the request layer must never flash it', function () {
        withOldInput(['password' => 'secret-password']);

        $this->withViewErrors([])
            ->blade('<x-auth.password />')
            ->assertSee('value="secret-password"', false);
    });
})->group('feature', 'components');

describe('user menu', function () {
    it('offers login and register to guests', function () {
        $this->withViewErrors([])->blade('<x-user-menu />')->assertSee('Login')->assertSee('Register')->assertDontSee('Logout');
    });

    it('offers a logout form to the authenticated user', function () {
        $this->actingAs(User::factory()->create(['first_name' => 'Jane']));

        $this->withViewErrors([])->blade('<x-user-menu />')
            ->assertSee('data-test="Logout"', false)
            ->assertSee('action="/logout"', false)
            ->assertSee('alt="Jane"', false)
            ->assertDontSee('Register');
    });
})->group('feature', 'components');

describe('navigation', function () {
    it('shows the admin link only to the admin', function () {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->blade('<x-nav-bar />')->assertSee('href="/admin"', false);
        $this->actingAs($user)->blade('<x-nav-bar />')->assertDontSee('href="/admin"', false);
    });

    it('renders the theme picker with light and dark options', function () {
        $this->blade('<x-theme />')->assertSee('Theme')->assertSee('aria-label="Light"', false)->assertSee('aria-label="Dark"', false);
    });
})->group('feature', 'components');
