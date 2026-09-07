<?php

use App\Enums\IdeaState;
use App\Http\Requests\IdeaRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Tests\TestCase;

it('trims registration fields before validation', function () {
    $request = StoreRegisterRequest::create('/register', 'POST', [
        'first_name' => ' Jane ',
        'last_name' => ' Doe ',
        'email' => ' jane@example.com ',
        'password' => ' password ',
        'password_confirmation' => ' password ',
    ]);

    $prepareForValidation = new ReflectionMethod(StoreRegisterRequest::class, 'prepareForValidation');
    $prepareForValidation->invoke($request);

    expect($request->all())->toMatchArray([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
});

it('normalizes missing registration fields to empty strings', function () {
    $request = StoreRegisterRequest::create('/register', 'POST');

    $prepareForValidation = new ReflectionMethod(StoreRegisterRequest::class, 'prepareForValidation');
    $prepareForValidation->invoke($request);

    expect($request->all())->toMatchArray([
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
    ]);
});

it('trims idea fields before validation', function () {
    $request = IdeaRequest::create('/ideas/create', 'POST', [
        'title' => ' A title ',
        'description' => ' A long enough description. ',
        'state' => ' active ',
    ]);

    $prepareForValidation = new ReflectionMethod(IdeaRequest::class, 'prepareForValidation');
    $prepareForValidation->invoke($request);

    expect($request->all())->toMatchArray([
        'title' => 'A title',
        'description' => 'A long enough description.',
        'state' => 'active',
    ]);

    $emptyRequest = IdeaRequest::create('/ideas/create', 'POST');
    $prepareForValidation->invoke($emptyRequest);

    expect($emptyRequest->all())->toMatchArray([
        'title' => '',
        'description' => '',
        'state' => '',
    ]);
});

it('keeps the registration validation contract explicit', function () {
    $rules = (new StoreRegisterRequest)->rules();

    expect($rules['first_name'])->toContain('required', 'string', 'min:2', 'max:100')
        ->and($rules['last_name'])->toContain('required', 'string', 'min:2', 'max:100')
        ->and($rules['email'])->toContain('required', 'string', 'email', 'max:255', 'unique:users,email')
        ->and($rules['password'])->toContain('required', 'string', 'confirmed')
        ->and($rules['password'])->toHaveCount(4);
});

it('keeps the idea validation contract explicit', function () {
    $rules = (new IdeaRequest)->rules();

    expect($rules['title'])->toContain('required', 'string', 'min:3', 'max:255')
        ->and($rules['description'])->toContain('required', 'string', 'min:10')
        ->and($rules['state'])->toContain('required');
});

it('rejects a short idea title', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->from('/ideas/create')->post('/ideas/create', [
        'title' => 'ab', 'description' => 'A valid description here.', 'state' => IdeaState::ACTIVE->value,
    ]);

    $response->assertRedirect('/ideas/create')->assertSessionHasErrors('title');
})->group('feature');

it('rejects a short idea description', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->from('/ideas/create')->post('/ideas/create', [
        'title' => 'A valid title', 'description' => 'short', 'state' => IdeaState::ACTIVE->value,
    ]);

    $response->assertRedirect('/ideas/create')->assertSessionHasErrors('description');
})->group('feature');

it('rejects an invalid idea state', function () {
    /** @var TestCase $this */
    $response = $this->actingAs(User::factory()->create())->from('/ideas/create')->post('/ideas/create', [
        'title' => 'A valid title', 'description' => 'A valid description here.', 'state' => 'unknown',
    ]);

    $response->assertRedirect('/ideas/create')->assertSessionHasErrors('state');
})->group('feature');

it('rejects a short registration first name', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', [
        'first_name' => 'A', 'last_name' => 'Doe', 'email' => 'valid@example.com',
        'password' => 'password', 'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors('first_name');
})->group('auth', 'feature');

it('rejects a short registration last name', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', [
        'first_name' => 'Jane', 'last_name' => 'D', 'email' => 'valid@example.com',
        'password' => 'password', 'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors('last_name');
})->group('auth', 'feature');

it('rejects registration values over their maximum lengths', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', [
        'first_name' => str_repeat('a', 101),
        'last_name' => str_repeat('b', 101),
        'email' => str_repeat('c', 247).'@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/register')
        ->assertSessionHasErrors(['first_name', 'last_name', 'email']);
})->group('auth', 'feature');

it('rejects an invalid registration email', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', [
        'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'invalid',
        'password' => 'password', 'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors('email');
})->group('auth', 'feature');

it('rejects mismatched registration passwords', function () {
    /** @var TestCase $this */
    $response = $this->from('/register')->post('/register', [
        'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'valid@example.com',
        'password' => 'password', 'password_confirmation' => 'different',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors('password');
})->group('auth', 'feature');
