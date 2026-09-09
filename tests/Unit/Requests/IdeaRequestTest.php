<?php

declare(strict_types=1);

use App\Enums\IdeaState;
use App\Http\Requests\IdeaRequest;
use Illuminate\Support\Facades\Validator;

covers(IdeaRequest::class);

/**
 * @return array<string, string>
 */
function validIdeaInput(): array
{
    return [
        'title' => 'A valid title',
        'description' => 'A description that is long enough.',
        'state' => 'active',
    ];
}

it('authorizes every authenticated request', function () {
    expect((new IdeaRequest)->authorize())->toBeTrue();
})->group('unit', 'requests');

it('accepts a valid payload', function () {
    $validator = Validator::make(validIdeaInput(), (new IdeaRequest)->rules());

    expect($validator->passes())->toBeTrue();
})->group('unit', 'requests');

it('accepts every idea state', function (IdeaState $state) {
    $validator = Validator::make([...validIdeaInput(), 'state' => $state->value], (new IdeaRequest)->rules());

    expect($validator->passes())->toBeTrue();
})->with('idea states')->group('unit', 'requests');

it('rejects an invalid field with the expected message', function (array $overrides, string $field, string $message) {
    $validator = Validator::make([...validIdeaInput(), ...$overrides], (new IdeaRequest)->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first($field))->toBe($message);
})->with('invalid idea payloads')->group('unit', 'requests');

it('accepts boundary lengths for title and description', function (array $overrides) {
    $validator = Validator::make([...validIdeaInput(), ...$overrides], (new IdeaRequest)->rules());

    expect($validator->passes())->toBeTrue();
})->with([
    'title of exactly 3 characters' => [['title' => 'abc']],
    'title of exactly 255 characters' => [['title' => str_repeat('a', 255)]],
    'description of exactly 10 characters' => [['description' => str_repeat('d', 10)]],
])->group('unit', 'requests');

it('rejects a non-string title and description even when their size would pass', function () {
    $validator = Validator::make([...validIdeaInput(), 'title' => ['a', 'b', 'c'], 'description' => 12345678901], (new IdeaRequest)->rules());

    expect($validator->errors()->first('title'))->toBe('The title field must be a string.')
        ->and($validator->errors()->first('description'))->toBe('The description field must be a string.');
})->group('unit', 'requests');

it('trims every field before validation', function () {
    $request = IdeaRequest::create('/ideas/create', 'POST', [
        'title' => ' A title ',
        'description' => ' A long enough description. ',
        'state' => ' active ',
    ]);

    (new ReflectionMethod(IdeaRequest::class, 'prepareForValidation'))->invoke($request);

    expect($request->all())->toBe([
        'title' => 'A title',
        'description' => 'A long enough description.',
        'state' => 'active',
    ]);
})->group('unit', 'requests');

it('normalizes missing fields to empty strings so the required rule fires', function () {
    $request = IdeaRequest::create('/ideas/create', 'POST');

    (new ReflectionMethod(IdeaRequest::class, 'prepareForValidation'))->invoke($request);

    expect($request->all())->toBe(['title' => '', 'description' => '', 'state' => '']);
})->group('unit', 'requests');
