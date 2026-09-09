<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

it('resets every migration and migrates again without errors', function () {
    $this->artisan('migrate:reset')->assertExitCode(0);
    expect(Schema::hasTable('ideas'))->toBeFalse()
        ->and(Schema::hasTable('users'))->toBeFalse();

    $this->artisan('migrate')->assertExitCode(0);
    expect(Schema::hasTable('ideas'))->toBeTrue()
        ->and(Schema::hasColumn('ideas', 'state'))->toBeTrue();
})->group('feature', 'database');
