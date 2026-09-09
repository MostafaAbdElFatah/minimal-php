<?php

declare(strict_types=1);

use App\Models\User;
use Pest\Browser\Api\AwaitableWebpage;

/**
 * Create the admin user (the first user in the table) and authenticate as them.
 */
function actingAsAdmin(): User
{
    $admin = User::factory()->create();

    test()->actingAs($admin);

    return $admin;
}

/**
 * Create a regular (non-admin) user and authenticate as them.
 *
 * An admin already exists so the returned user is never the first record.
 */
function actingAsUser(): User
{
    User::factory()->create();
    $user = User::factory()->create();

    test()->actingAs($user);

    return $user;
}

/**
 * Sign a user in through the real login form in a browser test.
 */
function loginAs(User $user, string $password = 'password'): AwaitableWebpage
{
    return visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', $password)
        ->press('@login')
        ->assertPathIs('/');
}

/**
 * Flash old input the way a failed form submission would, so old() resolves inside $this->blade().
 *
 * @param  array<string, mixed>  $input
 */
function withOldInput(array $input): void
{
    $store = app('session.store');
    $store->flashInput($input);
    request()->setLaravelSession($store);
}
