<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

it('creates an admin user from the console command', function () {
    /** @var TestCase $this */
    $this->artisan('make:admin', [
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ])
        ->expectsOutput('Admin user created: admin@example.com')
        ->assertExitCode(0);

    $user = User::where('email', 'admin@example.com')->firstOrFail();

    expect($user->first_name)->toBe('Admin')
        ->and($user->last_name)->toBe('User')
        ->and(Hash::check('secret-password', $user->password))->toBeTrue();
});

it('updates an existing user when creating an admin with the same email', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'first_name' => 'Old',
        'last_name' => 'Name',
        'password' => 'old-password',
    ]);

    $this->artisan('make:admin', [
        'email' => 'admin@example.com',
        'password' => 'new-password',
    ])
        ->expectsOutput('Admin user created: admin@example.com')
        ->assertExitCode(0);

    $user->refresh();

    expect(User::where('email', 'admin@example.com')->count())->toBe(1)
        ->and($user->first_name)->toBe('Admin')
        ->and($user->last_name)->toBe('User')
        ->and(Hash::check('new-password', $user->password))->toBeTrue();
});
