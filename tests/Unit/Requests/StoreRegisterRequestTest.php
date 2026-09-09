<?php

declare(strict_types=1);

use App\Http\Requests\StoreRegisterRequest;
use Illuminate\Support\Facades\Validator;

covers(StoreRegisterRequest::class);

/**
 * The rules without the database-backed `unique:users,email` rule, which is
 * covered by the feature test in tests/Feature/Requests/StoreRegisterRequestTest.php.
 *
 * @return array<string, array<int, mixed>>
 */
function registrationRulesWithoutDatabase(): array
{
    $rules = (new StoreRegisterRequest)->rules();
    $rules['email'] = array_values(array_filter(
        $rules['email'],
        fn (mixed $rule): bool => ! (is_string($rule) && str_starts_with($rule, 'unique:')),
    ));

    return $rules;
}

/**
 * @return array<string, string>
 */
function validRegistrationInput(): array
{
    return [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'jane-password',
        'password_confirmation' => 'jane-password',
    ];
}

it('accepts a valid registration payload', function () {
    $validator = Validator::make(validRegistrationInput(), registrationRulesWithoutDatabase());

    expect($validator->passes())->toBeTrue();
})->group('unit', 'requests');

it('rejects an invalid name', function (string $field, string $value, string $message) {
    $validator = Validator::make([...validRegistrationInput(), $field => $value], registrationRulesWithoutDatabase());

    expect($validator->errors()->first($field))->toBe($message);
})->with([
    'missing first name' => ['first_name', '', 'The first name field is required.'],
    'first name of 1 character' => ['first_name', 'J', 'The first name field must be at least 2 characters.'],
    'first name over 100 characters' => ['first_name', str_repeat('a', 101), 'The first name field must not be greater than 100 characters.'],
    'missing last name' => ['last_name', '', 'The last name field is required.'],
    'last name of 1 character' => ['last_name', 'D', 'The last name field must be at least 2 characters.'],
    'last name over 100 characters' => ['last_name', str_repeat('a', 101), 'The last name field must not be greater than 100 characters.'],
])->group('unit', 'requests');

it('rejects an invalid email', function (string $email) {
    $validator = Validator::make([...validRegistrationInput(), 'email' => $email], registrationRulesWithoutDatabase());

    expect($validator->errors()->has('email'))->toBeTrue();
})->with('invalid emails')->group('unit', 'requests');

it('rejects an invalid password', function (string $password, string $message) {
    $validator = Validator::make([
        ...validRegistrationInput(),
        'password' => $password,
        'password_confirmation' => $password,
    ], registrationRulesWithoutDatabase());

    expect($validator->errors()->first('password'))->toBe($message);
})->with('invalid passwords')->group('unit', 'requests');

it('rejects a mismatched password confirmation', function () {
    $validator = Validator::make([
        ...validRegistrationInput(),
        'password_confirmation' => 'different-password',
    ], registrationRulesWithoutDatabase());

    expect($validator->errors()->first('password'))->toBe('The password field confirmation does not match.');
})->group('unit', 'requests');

it('accepts a password of exactly 8 characters', function () {
    $validator = Validator::make([
        ...validRegistrationInput(),
        'password' => 'abcdefgh',
        'password_confirmation' => 'abcdefgh',
    ], registrationRulesWithoutDatabase());

    expect($validator->passes())->toBeTrue();
})->group('unit', 'requests');

it('trims every field before validation', function () {
    $request = StoreRegisterRequest::create('/register', 'POST', [
        'first_name' => ' Jane ',
        'last_name' => ' Doe ',
        'email' => ' jane@example.com ',
        'password' => ' password ',
        'password_confirmation' => ' password ',
    ]);

    (new ReflectionMethod(StoreRegisterRequest::class, 'prepareForValidation'))->invoke($request);

    expect($request->all())->toBe([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
})->group('unit', 'requests');

it('normalizes missing fields to empty strings so the required rule fires', function () {
    $request = StoreRegisterRequest::create('/register', 'POST');

    (new ReflectionMethod(StoreRegisterRequest::class, 'prepareForValidation'))->invoke($request);

    expect($request->all())->toBe([
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
    ]);
})->group('unit', 'requests');

it('rejects non-string values for every text field', function (string $field) {
    $validator = Validator::make([...validRegistrationInput(), $field => ['not', 'a', 'string']], registrationRulesWithoutDatabase());

    expect($validator->errors()->first($field))->toBe('The '.str_replace('_', ' ', $field).' field must be a string.');
})->with(['first_name', 'last_name', 'email', 'password'])->group('unit', 'requests');
