<?php

use App\Models\User;
use Tests\TestCase;

it('returns not found for guests visiting the admin page', function () {
    /** @var TestCase $this */
    $this->get('/admin')->assertRedirect(route('login'));
})->group('feature');

it('allows the first user to visit the admin page', function () {
    /** @var TestCase $this */
    $admin = User::factory()->create();

    $this->actingAs($admin)->get('/admin')->assertOk()->assertViewIs('admin');
})->group('feature');

it('hides the admin page from non-admin users', function () {
    /** @var TestCase $this */
    User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertNotFound();
})->group('feature');
