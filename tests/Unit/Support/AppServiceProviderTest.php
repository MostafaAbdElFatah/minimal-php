<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('the view-admin gate is registered', function () {
    expect(Gate::has('view-admin'))->toBeTrue();
})->group('unit', 'policies');

test('the view-admin gate allows the first user and hides the page from everyone else', function (int $id, bool $allowed) {
    $user = (new User)->forceFill(['id' => $id]);

    $response = Gate::forUser($user)->inspect('view-admin');

    expect($response->allowed())->toBe($allowed)
        ->and($response->status())->toBe($allowed ? null : 404);
})->with([
    'first user is admin' => [1, true],
    'second user is not' => [2, false],
])->group('unit', 'policies');
