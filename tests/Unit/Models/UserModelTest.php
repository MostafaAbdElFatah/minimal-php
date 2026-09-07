<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('user has many ideas', function () {
    $user = User::factory()->create();
    Idea::factory()->count(2)->for($user)->create();

    expect($user->ideas())->toBeInstanceOf(HasMany::class)
        ->and($user->ideas)->toHaveCount(2);
})->group('unit');

test('user hides password and remember token from serialized output', function () {
    $user = User::factory()->create();

    expect($user->toArray())->not->toHaveKeys(['password', 'remember_token']);
})->group('unit');

test('user hashes a newly assigned password', function () {
    $user = User::factory()->create(['password' => 'plain-password']);

    expect($user->getRawOriginal('password'))->not->toBe('plain-password')
        ->and($user->getAuthPassword())->not->toBe('plain-password');
})->group('unit');
