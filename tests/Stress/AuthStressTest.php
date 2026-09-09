<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Auth stress thresholds
|--------------------------------------------------------------------------
| Target: a dedicated, seeded environment (STRESS_URL, default APP_URL).
| Never run against production or the in-memory test database.
|
|   GET /login      50 VUs x 10s   0 failures, median < 200 ms, p95 < 500 ms
|   GET /register   20 VUs x 10s   0 failures, p95 < 500 ms
|
| POST /login is not stressed: every form post needs a per-session CSRF token,
| which k6 cannot obtain without a scripted token dance. Add it once a
| token-less (API) login exists.
|
| Baselines live in reports/stress/*.json; a p95 regression > 20 % fails.
*/

use function Pest\Stressless\stress;

it('serves the login page under 50 concurrent users', function () {
    $result = stress(stressUrl('/login'))
        ->concurrently(50)
        ->for(10)->seconds();

    expect($result->requests()->failed()->count())->toBe(0)
        ->and($result->requests()->duration()->med())->toBeLessThan(200)
        ->and($result->requests()->duration()->p95())->toBeLessThan(500)
        ->and($result->requests()->duration()->p95())->toBeLessThan(baselineP95('login-page') * 1.2);
})->group('stress', 'auth');

it('serves the registration page under 20 concurrent users', function () {
    $result = stress(stressUrl('/register'))
        ->concurrently(20)
        ->for(10)->seconds();

    expect($result->requests()->failed()->count())->toBe(0)
        ->and($result->requests()->duration()->p95())->toBeLessThan(500)
        ->and($result->requests()->duration()->p95())->toBeLessThan(baselineP95('register-page') * 1.2);
})->group('stress', 'auth');
