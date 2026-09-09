<?php

declare(strict_types=1);
use Illuminate\Console\Command;

arch('commands extend Command and expose handle')
    ->expect('App\Console\Commands')
    ->toExtend(Command::class)
    ->toHaveMethod('handle')
    ->group('console');

arch('commands are not used elsewhere in the application')
    ->expect('App\Console\Commands')->toOnlyBeUsedIn(['App\Console'])
    ->group('console');
