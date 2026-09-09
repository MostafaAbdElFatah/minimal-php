<?php

use App\Http\Controllers\Auth\SessionsController;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

it('passes the intended login defaults and validation rules to the framework', function () {
    /** @var TestCase $this */
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('input')->with('email', '')->once()->andReturn('');
    $request->shouldReceive('input')->with('password', '')->once()->andReturn('');
    $request->shouldReceive('merge')->with([
        'email' => '',
        'password' => '',
    ])->once();
    $request->shouldReceive('validate')->with(Mockery::on(function (array $rules): bool {
        return $rules === [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }))->once()->andReturn([]);
    $request->shouldReceive('boolean')->with('remember')->once()->andReturn(false);

    Auth::shouldReceive('attempt')->with([], false)->once()->andReturn(false);

    (new SessionsController)->store($request);
})->group('auth', 'feature');

it('trims the login email before validation', function () {
    /** @var TestCase $this */
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('input')->with('email', '')->once()->andReturn(' user@example.com ');
    $request->shouldReceive('input')->with('password', '')->once()->andReturn('');
    $request->shouldReceive('merge')->with([
        'email' => 'user@example.com',
        'password' => '',
    ])->once();
    $request->shouldReceive('validate')->andReturn([]);
    $request->shouldReceive('boolean')->with('remember')->andReturn(false);

    Auth::shouldReceive('attempt')->with([], false)->once()->andReturn(false);

    (new SessionsController)->store($request);
})->group('auth', 'feature');

it('regenerates the session after a successful login', function () {
    /** @var TestCase $this */
    $session = Mockery::mock(Session::class);
    $session->shouldReceive('regenerate')->once();

    $request = Mockery::mock(Request::class);
    $request->shouldReceive('input')->with('email', '')->andReturn('user@example.com');
    $request->shouldReceive('input')->with('password', '')->andReturn('password');
    $request->shouldReceive('merge')->once();
    $request->shouldReceive('validate')->andReturn([
        'email' => 'user@example.com',
        'password' => 'password',
    ]);
    $request->shouldReceive('boolean')->with('remember')->andReturn(false);
    $request->shouldReceive('session')->once()->andReturn($session);

    Auth::shouldReceive('attempt')->with([
        'email' => 'user@example.com',
        'password' => 'password',
    ], false)->once()->andReturnTrue();

    (new SessionsController)->store($request);
})->group('auth', 'feature');

it('renders the login form for guests', function () {
    /** @var TestCase $this */
    $response = $this->get(route('login'));

    $response->assertOk()->assertViewIs('auth.login');
})->group('auth', 'feature');

it('authenticates a user with valid credentials', function () {
    /** @var TestCase $this */
    $user = User::factory()->create(['password' => 'password']);
    $sessionId = session()->getId();

    $response = $this->from(route('login'))->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => '1',
    ]);

    $response->assertRedirect('/')->assertSessionHas('status', 'You are now logged in.');
    $this->assertAuthenticatedAs($user);
    expect(session()->getId())->not->toBe($sessionId);
})->group('auth', 'feature');

it('trims login credentials before attempting authentication', function () {
    /** @var TestCase $this */
    $user = User::factory()->create(['password' => 'password']);

    $this->post(route('login'), [
        'email' => " {$user->email} ",
        'password' => ' password ',
    ])->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
})->group('auth', 'feature');

it('rejects invalid login credentials without authenticating', function () {
    /** @var TestCase $this */
    $user = User::factory()->create(['password' => 'password']);

    $response = $this->from(route('login'))->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors(['email'])
        ->assertSessionHasInput('email', $user->email);
    $this->assertGuest();
})->group('auth', 'feature');

it('rejects malformed login input', function () {
    /** @var TestCase $this */
    $response = $this->from(route('login'))->post(route('login'), [
        'email' => 'not-an-email',
        'password' => '',
    ]);

    $response->assertRedirect(route('login'))->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
})->group('auth', 'feature');

it('rejects an overly long login email', function () {
    /** @var TestCase $this */
    $response = $this->from(route('login'))->post(route('login'), [
        'email' => str_repeat('a', 248).'@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'))->assertSessionHasErrors('email');
    $this->assertGuest();
})->group('auth', 'feature');

it('logs out the authenticated user and invalidates the session', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->actingAs($user);
    session(['logout-marker' => 'present']);
    $sessionId = session()->getId();
    $csrfToken = session()->token();

    $response = $this->delete(route('logout'));

    $response->assertRedirect('/')->assertSessionHas('status', 'You have been logged out.');
    $this->assertGuest();
    expect(session()->has('logout-marker'))->toBeFalse();
    expect(session()->getId())->not->toBe($sessionId);
    expect(session()->token())->not->toBe($csrfToken);
})->group('auth', 'feature');

it('regenerates the session token when logging out', function () {
    /** @var TestCase $this */
    $session = Mockery::mock(Session::class);
    $session->shouldReceive('invalidate')->once();
    $session->shouldReceive('regenerateToken')->once();

    $request = Mockery::mock(Request::class);
    $request->shouldReceive('session')->twice()->andReturn($session);
    Auth::shouldReceive('logout')->once();

    (new SessionsController)->destroy($request);
})->group('auth', 'feature');
