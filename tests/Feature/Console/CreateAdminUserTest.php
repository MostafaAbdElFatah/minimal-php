<?php

declare(strict_types=1);

use App\Console\Commands\CreateAdminUser;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Exception\RuntimeException;

covers(CreateAdminUser::class);

it('creates an admin user with a hashed password', function () {
    $this->artisan('make:admin', ['email' => 'admin@example.com', 'password' => 'secret-password'])
        ->expectsOutput('Admin user created: admin@example.com')
        ->assertExitCode(0);

    $user = User::firstWhere('email', 'admin@example.com');
    expect($user->first_name)->toBe('Admin')
        ->and($user->last_name)->toBe('User')
        ->and($user->isAdmin())->toBeTrue()
        ->and(Hash::check('secret-password', $user->password))->toBeTrue();
})->group('feature', 'console');

it('updates the existing user with the same email instead of duplicating', function () {
    $user = User::factory()->create(['email' => 'admin@example.com', 'first_name' => 'Old', 'password' => 'old-password']);

    $this->artisan('make:admin', ['email' => 'admin@example.com', 'password' => 'new-password'])
        ->assertExitCode(0);

    $this->assertDatabaseCount('users', 1);
    expect($user->fresh()->first_name)->toBe('Admin')
        ->and(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
})->group('feature', 'console');

it('fails when the arguments are missing', function (array $arguments) {
    expect(fn () => $this->artisan('make:admin', $arguments))->toThrow(RuntimeException::class);

    $this->assertDatabaseCount('users', 0);
})->with([
    'no arguments' => [[]],
    'password missing' => [['email' => 'admin@example.com']],
])->group('feature', 'console');

it('registers no scheduled tasks', function () {
    expect(app(Schedule::class)->events())->toBeEmpty();
})->group('feature', 'console');
