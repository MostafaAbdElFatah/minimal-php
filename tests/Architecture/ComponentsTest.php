<?php

declare(strict_types=1);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

arch('blade components extend Component and render a view')
    ->expect('App\View\Components')->toExtend(Component::class)
    ->toHaveMethod('render')
    ->group('components');

arch('blade components do not touch the database or the request')
    ->expect('App\View\Components')->not->toUse([DB::class, Request::class])
    ->group('components');
