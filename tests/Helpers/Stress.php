<?php

declare(strict_types=1);

/**
 * Resolve the base URL of the dedicated stress environment.
 */
function stressUrl(string $path): string
{
    $base = getenv('STRESS_URL') ?: config('app.url');

    return rtrim((string) $base, '/').$path;
}

/**
 * Read the recorded p95 (ms) for an endpoint from reports/stress/{name}.json.
 *
 * Returns INF when no baseline has been recorded yet so the first run only
 * checks the absolute thresholds.
 */
function baselineP95(string $name): float
{
    $file = base_path("reports/stress/{$name}.json");

    if (! is_file($file)) {
        return INF;
    }

    /** @var array{p95?: float|int} $baseline */
    $baseline = json_decode((string) file_get_contents($file), true) ?: [];

    return (float) ($baseline['p95'] ?? INF);
}
