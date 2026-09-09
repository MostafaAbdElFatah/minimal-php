<?php

declare(strict_types=1);
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

arch('controllers extend the application controller')
    ->expect('App\Http\Controllers')->toExtend('App\Http\Controllers\Controller')
    ->ignoring('App\Http\Controllers\Controller')->group('controllers');

arch('controllers are suffixed')
    ->expect('App\Http\Controllers')->toHaveSuffix('Controller')->group('controllers');

arch('controllers do not query the database directly')
    ->expect('App\Http\Controllers')->not->toUse(DB::class)
    ->group('controllers');

arch('controllers do not use the Validator facade')
    ->expect('App\Http\Controllers')->not->toUse(Validator::class)
    ->group('controllers');

arch('controllers are not used outside the routing layer')
    ->expect('App\Http\Controllers')->toOnlyBeUsedIn(['App\Http\Controllers'])
    ->group('controllers');
