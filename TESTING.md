# Testing

The suite is built on **Pest 5** with the Laravel, Browser (Playwright), Arch, Mutate,
Type-Coverage and Stressless (k6) plugins. Every test is isolated, deterministic and
carries at least one *layer* group and one *domain* group.

## Prerequisites

| Need | How |
| --- | --- |
| PHP 8.5 + sqlite | already required by the app |
| Coverage driver | PCOV (preferred, `pecl install pcov`) or Xdebug. The composer scripts set `XDEBUG_MODE=coverage`; with PCOV installed remove that prefix or leave it, it is harmless. |
| Browser tests | `npm install` then `npx playwright install chromium` |
| Stress tests | k6 is bundled with the stressless plugin. Point `STRESS_URL` at a **dedicated, seeded** environment; the suite skips itself when the variable is missing. |

## Running

| Command | What it runs |
| --- | --- |
| `composer test` | Unit + Feature + Architecture in parallel (default, fast) |
| `composer test:unit` / `test:feature` / `test:arch` | one suite |
| `composer test:browser` | Playwright journeys (desktop + mobile) |
| `composer test:random` | default suite in random order |
| `composer test:coverage` | line coverage, fails under 90 %, HTML in `reports/coverage`, Clover in `reports/clover.xml` |
| `composer test:types` | type coverage, fails under 100 % |
| `composer test:mutate` | mutation testing, fails under 80 % |
| `composer test:mutate:models` | mutation testing for the `models` group, fails under 90 % |
| `STRESS_URL=https://staging.example.com composer test:stress` | k6 load scenarios |
| `composer test:all` | arch → coverage → types → browser |

Narrow a run with a path or `--filter`: `vendor/bin/pest tests/Feature/Auth --filter=remember`.
Focused mutation: `vendor/bin/pest --mutate --covered-only --class=App\\Policies\\IdeaPolicy`.

## Layout

```
tests/
├── Pest.php            bindings: Unit (no DB) · Feature/Browser (RefreshDatabase) · Architecture · Stress (skips without STRESS_URL)
├── TestCase.php        enables preventLazyLoading / preventAccessingMissingAttributes / preventSilentlyDiscardingAttributes
├── Datasets/           shared datasets (Emails, Passwords, Ideas, Pagination)
├── Helpers/            actingAsAdmin(), actingAsUser(), loginAs(), withOldInput(), stressUrl(), baselineP95()
├── Fixtures/           static files
├── Unit/               pure logic; must not touch the database, HTTP or filesystem
├── Feature/            boots the app with an in-memory sqlite database
│   ├── Auth/ Controllers/ Requests/ Policies/ Models/ Database/ Components/ Console/ Middleware/ Jobs/
├── Browser/            Playwright journeys (Auth/, Ideas/, SmokeTest)
├── Architecture/       one arch() file per concern
└── Stress/             stressless scenarios, thresholds documented in each file header
```

## Group taxonomy

Layer groups: `unit`, `feature`, `browser`, `architecture`, `stress`.
Domain groups: `models`, `database`, `components`, `console`, `controllers`, `policies`, `requests`, `auth`, `middleware`, `jobs`.

Every test declares one of each, e.g. `->group('feature', 'controllers')`. Run a domain with
`vendor/bin/pest --group=policies`.

## Rules

- `declare(strict_types=1);` in every test file; `covers(Foo::class)` in every file that exercises a class.
- Each test creates its own data through factories (`for()`, `recycle()`, `sequence()`, named states).
- Fake external effects (`Mail::fake()`, `Notification::fake()`, `Queue::fake()`, `Http::fake()`...); freeze time with `$this->travelTo()`.
- Unit tests never touch the database; move anything that needs it to `tests/Feature`.
- Browser tests use `@data-test` selectors (`@login`, `@register`, `@Logout`, `@new-idea`, `@save`, `@edit`, `@delete`, `@delete-all`), call `assertNoJavaScriptErrors()` on every page and cover the primary journeys on `->on()->mobile()` too.
- The value matrix for a rule lives in the Unit request test; the Feature request test keeps one case per rule that proves the route applies it and shows the user-facing message.
- The permission matrix lives in the policy tests; controller tests keep one refused case per endpoint (`IdeaAuthorizationTest`).

## Adding tests for a new…

| Thing | Files to add |
| --- | --- |
| Model | `tests/Unit/Models/{Name}Test.php` (casts, accessors, fillable, relationship types) and `tests/Feature/Models/{Name}Test.php` (factory, relationships, scopes, cascade). Add `tests/Feature/Database/FactoriesTest.php` and `SchemaTest.php` cases. Every model needs a factory. |
| Controller | `tests/Feature/Controllers/{Name}ControllerTest.php` with a `describe()` per action: happy path, guest redirect, 404/403 for foreign records, validation failure, side effects. Add the endpoint to `IdeaAuthorizationTest`-style datasets. |
| Form request | `tests/Unit/Requests/{Name}RequestTest.php` (`Validator::make` matrix, `prepareForValidation`) and `tests/Feature/Requests/{Name}RequestTest.php` (route applies it, messages shown). |
| Policy | `tests/Unit/Policies/{Name}PolicyTest.php` (in-memory models, `setRelation`) and `tests/Feature/Policies/{Name}PolicyTest.php` (Gate matrix dataset, discovery, 404 vs 403). |
| Command | `tests/Feature/Console/{Name}Test.php`: `$this->artisan()` happy path, failure exit code / exception, side effects, schedule registration. |
| Blade component | class logic in `tests/Unit/Components/ComponentClassesTest.php`; rendering with `$this->blade()` in `tests/Feature/Components/`. Use `withViewErrors()` and `withOldInput()` when the markup reads `$errors` or `old()`. |
| Browser journey | `tests/Browser/{Feature}/{Action}Test.php`; add `data-test` attributes to the Blade form rather than relying on CSS classes; a `script()` call runs immediately, so target the right form (`main form`) and assert the path before scripting. |
| Stress scenario | `tests/Stress/{Endpoint}StressTest.php`; document thresholds in the header, read the baseline with `baselineP95()`. |

## Known blockers (do not lower the thresholds)

| Blocker | Refactor | Estimate |
| --- | --- | --- |
| Type coverage is 93 %, not 100 % | add return types to every controller action and parameter types to the two closures (`IdeaRequest::rules`, `IdeaController::index`) | 20 min |
| `arch()->preset()->laravel()` ignores `IdeaController` and `CreateAdminUser` | move `destroyAll` to an invokable `DestroyAllIdeasController`; rename the command to `CreateAdminUserCommand` | 30 min |
| `StoreRegisterRequest` has no `authorize()`; `SessionsController` validates inline | add `authorize()`; move login validation to a `LoginRequest` | 30 min |
| App code has no `declare(strict_types=1)` | add it file by file and run the suite after each | 1 h |
| Login has no throttling, no `redirect()->intended()` | add `throttle` middleware / `RateLimiter`; use `intended('/')` | 30 min |

### Defects found by the suite (app behaviour, not test debt)

- `IdeaCard` / `IdeaStatus` class components received no `$idea`; fixed by adding `public Idea $idea` to their constructors. Any stale compiled view hid this (`php artisan view:clear`).
- The user menu read a non-existent `name` attribute; now uses `first_name`.
- `/welcome` evaluates `request('name')` at route registration time, so it always greets "Guest" (`StaticPagesTest` todo).
- Arrays posted as `password` reach `trim()` before validation and raise a 500 (`LoginTest` todo). The same pattern exists in both form requests' `prepareForValidation()`.
- Titles are HTML-escaped twice in `<title>` and in the card header (`x-layout title="{{ }}"` + `{{ $title }}`), showing literal `&lt;` to users.
- `Route::get('/login', [])` in `routes/web.php` is a dead duplicate; `@dump($tasks)` is left in `welcome.blade.php`.
- `IdeaSeeder` hard-codes `user_id = 2` and neither seeder is idempotent for factory rows (`SeedersTest` documents the real counts).

## Equivalent mutants (documented, not fixed)

| Mutant | Why it survives |
| --- | --- |
| `SessionsController` drop/unwrap `trim()` on email | the global `TrimStrings` middleware already trims it |
| `SessionsController` remove `string` rule on email / password | `email` rejects arrays anyway; an array password crashes in `trim()` first (see defects) |
| `SessionsController` remove `session()->regenerate()` | the array session driver issues a new id on every request, so the id always changes in tests |
| `SessionsController` remove `regenerateToken()` on logout | `StartSession` regenerates a missing token on the next request |
| `IdeaController::edit` remove `Gate::authorize` | the route already carries `can:update,idea` |
