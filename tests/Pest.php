<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case bindings
|--------------------------------------------------------------------------
|
| Unit tests boot the application but never touch the database. Feature and
| Browser tests refresh the in-memory database for every test. Architecture
| and Stress tests carry their layer group automatically.
|
*/

pest()->extend(TestCase::class)->in('Unit');

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Browser');

pest()->group('architecture')->in('Architecture');
pest()->extend(TestCase::class)
    ->group('stress')
    ->beforeEach(function (): void {
        if (getenv('STRESS_URL') === false || getenv('STRESS_URL') === '') {
            $this->markTestSkipped('Stress tests run only against a dedicated environment: set STRESS_URL (composer test:stress).');
        }
    })
    ->in('Stress');

pest()->browser()->timeout(10000);

/*
|--------------------------------------------------------------------------
| Helpers & datasets
|--------------------------------------------------------------------------
*/

foreach (glob(__DIR__.'/Helpers/*.php') ?: [] as $helper) {
    require_once $helper;
}
