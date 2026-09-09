<?php

declare(strict_types=1);

arch('models do not depend on controllers')
    ->expect('App\Models')->not->toUse('App\Http\Controllers')->group('models');

arch('models do not depend on requests or policies')
    ->expect('App\Models')->not->toUse(['App\Http\Requests', 'App\Policies'])->group('models');

arch('the http layer is only used by the http layer and providers')
    ->expect('App\Http')->toOnlyBeUsedIn(['App\Http', 'App\Providers']);

arch('application contains no debug calls')
    ->expect(['dd', 'dump', 'var_dump', 'ray', 'ddd'])->not->toBeUsed();

arch('application does not call env outside configuration')
    ->expect('App')->not->toUse('env');

arch('enums live in the Enums namespace and are backed')
    ->expect('App\Enums')->toBeEnums()->toBeStringBackedEnums();
