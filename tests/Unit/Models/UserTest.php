<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

covers(User::class);

it('has many ideas keyed by user_id', function () {
    $relation = (new User)->ideas();

    expect($relation)->toBeInstanceOf(HasMany::class)
        ->and($relation->getForeignKeyName())->toBe('user_id')
        ->and($relation->getRelated())->toBeInstanceOf(Idea::class);
})->group('unit', 'models');

it('treats only the first user as an admin', function (?int $id, bool $expected) {
    $user = (new User)->forceFill(['id' => $id]);

    expect($user->isAdmin())->toBe($expected);
})->with([
    'id 1 is admin' => [1, true],
    'id 2 is not' => [2, false],
    'unsaved user is not' => [null, false],
])->group('unit', 'models');

it('hides the password and remember token when serialized', function () {
    $user = User::factory()->make();

    expect($user->toArray())
        ->not->toHaveKey('password')
        ->not->toHaveKey('remember_token')
        ->toHaveKeys(['first_name', 'last_name', 'email', 'email_verified_at']);
})->group('unit', 'models');

it('hashes a plain password on assignment', function () {
    $user = new User(['password' => 'plain-password']);

    expect($user->password)->not->toBe('plain-password')
        ->and(Hash::check('plain-password', $user->password))->toBeTrue();
})->group('unit', 'models');

it('casts the email verification timestamp to Carbon', function () {
    $user = User::factory()->make(['email_verified_at' => '2026-01-01 12:00:00']);

    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class)
        ->and($user->email_verified_at->toDateTimeString())->toBe('2026-01-01 12:00:00');
})->group('unit', 'models');

it('reports an unverified factory user as unverified', function () {
    $user = User::factory()->unverified()->make();

    expect($user->email_verified_at)->toBeNull();
})->group('unit', 'models');
