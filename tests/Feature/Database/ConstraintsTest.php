<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\QueryException;

it('rejects a duplicate email at the database level', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    expect(fn () => User::factory()->create(['email' => 'duplicate@example.com']))
        ->toThrow(QueryException::class);
})->group('feature', 'database');

it('rejects an idea that references a missing user', function () {
    expect(fn () => Idea::factory()->create(['user_id' => 999999]))
        ->toThrow(QueryException::class);
})->group('feature', 'database');

it('cascades idea deletion when the owner is removed', function () {
    $user = User::factory()->create();
    Idea::factory()->count(2)->for($user)->create();

    $user->delete();

    $this->assertDatabaseCount('ideas', 0);
})->group('feature', 'database');

it('rejects a null title or description', function (array $attributes) {
    expect(fn () => Idea::factory()->create($attributes))->toThrow(QueryException::class);
})->with([
    'null title' => [['title' => null]],
    'null description' => [['description' => null]],
])->group('feature', 'database');
