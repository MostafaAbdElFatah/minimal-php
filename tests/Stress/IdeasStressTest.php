<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Ideas stress thresholds
|--------------------------------------------------------------------------
| Target: a dedicated, seeded environment (STRESS_URL, default APP_URL).
|
|   GET /          (guest, redirects to /login)  30 VUs x 10s  0 failures, p95 < 500 ms
|   GET /about                                   30 VUs x 10s  0 failures, median < 150 ms
|
| Authenticated journeys need a session cookie; add them once a token-based
| login endpoint exists. Baselines live in reports/stress/*.json.
*/

use function Pest\Stressless\stress;

it('redirects guests from the idea index under load', function () {
    $result = stress(stressUrl('/'))
        ->concurrently(30)
        ->for(10)->seconds();

    expect($result->requests()->failed()->count())->toBe(0)
        ->and($result->requests()->duration()->p95())->toBeLessThan(500)
        ->and($result->requests()->duration()->p95())->toBeLessThan(baselineP95('ideas-index-guest') * 1.2);
})->group('stress', 'controllers');

it('serves the static about page under load', function () {
    $result = stress(stressUrl('/about'))
        ->concurrently(30)
        ->for(10)->seconds();

    expect($result->requests()->failed()->count())->toBe(0)
        ->and($result->requests()->duration()->med())->toBeLessThan(150)
        ->and($result->requests()->duration()->p95())->toBeLessThan(baselineP95('about-page') * 1.2);
})->group('stress', 'controllers');
