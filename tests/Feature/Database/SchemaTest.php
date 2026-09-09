<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

test('the users table has the authentication columns', function () {
    expect(Schema::hasColumns('users', [
        'id', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at',
    ]))->toBeTrue();
})->group('feature', 'database');

test('the ideas table has the ownership and state columns', function () {
    expect(Schema::hasColumns('ideas', ['id', 'user_id', 'title', 'description', 'state', 'created_at', 'updated_at']))->toBeTrue();
})->group('feature', 'database');

test('critical columns have the expected types', function (string $table, string $column, string $type) {
    expect(Schema::getColumnType($table, $column))->toBe($type);
})->with([
    'users.email is varchar' => ['users', 'email', 'varchar'],
    'ideas.description is text' => ['ideas', 'description', 'text'],
    'ideas.state is varchar' => ['ideas', 'state', 'varchar'],
    'ideas.user_id is integer' => ['ideas', 'user_id', 'integer'],
])->group('feature', 'database');

test('the ideas state column defaults to pending', function () {
    $column = collect(Schema::getColumns('ideas'))->firstWhere('name', 'state');

    expect($column['default'])->toContain('pending')->and($column['nullable'])->toBeFalse();
})->group('feature', 'database');

test('users.email is unique', function () {
    $indexes = collect(Schema::getIndexes('users'))->filter(fn (array $index): bool => $index['unique'] && $index['columns'] === ['email']);

    expect($indexes)->not->toBeEmpty();
})->group('feature', 'database');

test('ideas.user_id references users and cascades on delete', function () {
    $foreignKey = collect(Schema::getForeignKeys('ideas'))->firstWhere('columns', ['user_id']);

    expect($foreignKey['foreign_table'])->toBe('users')
        ->and($foreignKey['foreign_columns'])->toBe(['id'])
        ->and($foreignKey['on_delete'])->toBe('cascade');
})->group('feature', 'database');

test('the framework support tables exist', function (string $table) {
    expect(Schema::hasTable($table))->toBeTrue();
})->with(['password_reset_tokens', 'sessions', 'cache', 'jobs', 'failed_jobs'])->group('feature', 'database');
