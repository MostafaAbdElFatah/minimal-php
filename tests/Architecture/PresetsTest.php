<?php

declare(strict_types=1);
use App\Console\Commands\CreateAdminUser;
use App\Http\Controllers\IdeaController;

arch()->preset()->php();
// Two documented blockers keep classes out of the Laravel preset (see TESTING.md):
//  - IdeaController exposes the non-resource action `destroyAll` (move to an invokable controller)
//  - CreateAdminUser lacks the `Command` suffix (rename to CreateAdminUserCommand)
arch()->preset()->laravel()->ignoring([
    IdeaController::class,
    CreateAdminUser::class,
]);
arch()->preset()->security();
