<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

test('migration creates the expected column types', function () {
    expect(Schema::getColumnType('users', 'email'))->toBe('varchar')
        ->and(Schema::getColumnType('ideas', 'description'))->toBe('text')
        ->and(Schema::getColumnType('ideas', 'state'))->toBe('varchar');
})->group('unit');

test('deleting a user cascades to their ideas', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $user->delete();

    $this->assertDatabaseMissing('ideas', ['id' => $idea->id]);
})->group('unit');

test('user email uniqueness is enforced by the database', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    expect(fn () => User::factory()->create(['email' => 'duplicate@example.com']))
        ->toThrow(QueryException::class);
})->group('unit');

test('the users table has the expected authentication columns', function () {
    expect(Schema::hasColumns('users', [
        'first_name',
        'last_name',
        'email',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
})->group('unit');

test('the ideas table has the expected ownership and state columns', function () {
    expect(Schema::hasColumns('ideas', [
        'user_id',
        'title',
        'description',
        'state',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
})->group('unit');
