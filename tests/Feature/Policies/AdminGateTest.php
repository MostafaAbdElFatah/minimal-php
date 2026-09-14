<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Gate;

it('lets the first user open the admin page', function (): void {
    actingAsAdmin();

    $this->get('/admin')->assertOk()->assertViewIs('admin')->assertSee('Private admin only area');
})->group('feature', 'policies', 'auth');

it('hides the admin page from every other user as 404', function (): void {
    actingAsUser();

    $this->get('/admin')->assertNotFound();
})->group('feature', 'policies', 'auth');

it('redirects guests to the login page', function (): void {
    $this->get('/admin')->assertRedirect(route('login'));
})->group('feature', 'policies', 'auth');

it('shows the admin link in the navigation only to the admin', function (): void {
    $admin = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)->get(route('home'))->assertSee('href="/admin"', false);
    $this->actingAs($user)->get(route('home'))->assertDontSee('href="/admin"', false);
})->group('feature', 'policies', 'components');

it('denies the gate for guests', function (): void {
    expect(Gate::forUser(null)->allows('view-admin'))->toBeFalse();
})->group('feature', 'policies');
