<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;
use Database\Seeders\IdeaSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

it('seeds the named admin and demo users with usable passwords', function () {
    $this->seed(UserSeeder::class);

    $admin = User::firstWhere('email', 'admin@example.com');
    $jane = User::firstWhere('email', 'jane@example.com');
    expect($admin->id)->toBe(1)
        ->and($admin->isAdmin())->toBeTrue()
        ->and(Hash::check('admin-password', $admin->password))->toBeTrue()
        ->and(Hash::check('jane-password', $jane->password))->toBeTrue();
    $this->assertDatabaseCount('users', 12);
})->group('feature', 'database');

it('does not duplicate the named users when the user seeder runs twice', function () {
    $this->seed(UserSeeder::class);
    $this->seed(UserSeeder::class);

    expect(User::where('email', 'admin@example.com')->count())->toBe(1)
        ->and(User::where('email', 'jane@example.com')->count())->toBe(1);
})->group('feature', 'database');

it('never assigns seeded ideas to the admin user', function () {
    $this->seed(UserSeeder::class);

    $this->seed(IdeaSeeder::class);

    expect(Idea::where('user_id', 1)->count())->toBe(0)
        ->and(Idea::where('user_id', 2)->count())->toBeGreaterThanOrEqual(200)
        ->and(Idea::count())->toBe(800);
})->group('feature', 'database');

it('seeds the full demo dataset from the database seeder', function () {
    $this->seed();

    // UserSeeder (12) + 10 extra users + 100 ideas that each create their own owner
    $this->assertDatabaseCount('users', 122);
    $this->assertDatabaseCount('ideas', 900);
    expect(Idea::whereNotIn('user_id', User::pluck('id'))->count())->toBe(0);
})->group('feature', 'database');
