<?php

declare(strict_types=1);

arch('policies are suffixed and only used by the auth layer')
    ->expect('App\Policies')->toHaveSuffix('Policy')
    ->toOnlyBeUsedIn(['App\Providers', 'App\Policies'])
    ->group('policies');

arch('policies only depend on models and auth responses')
    ->expect('App\Policies')->toOnlyUse(['App\Models', 'Illuminate\Auth\Access\Response'])
    ->group('policies');
