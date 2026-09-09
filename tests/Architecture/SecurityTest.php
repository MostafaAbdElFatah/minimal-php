<?php

declare(strict_types=1);

arch('no insecure functions')
    ->expect(['md5', 'sha1', 'eval', 'exec', 'shell_exec', 'system', 'passthru', 'unserialize', 'extract'])
    ->not->toBeUsed();

arch('mass assignment is explicit through fillable')
    ->expect('App\Models')->not->toHaveProperty('guarded')
    ->group('models');

arch('passwords are never hashed by hand')
    ->expect(['bcrypt', 'password_hash'])->not->toBeUsedIn('App\Http');
