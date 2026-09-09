<?php

declare(strict_types=1);
use Illuminate\Foundation\Http\FormRequest;

arch('form requests extend FormRequest and expose rules')
    ->expect('App\Http\Requests')
    ->toExtend(FormRequest::class)
    ->toHaveSuffix('Request')
    ->toHaveMethod('rules')
    ->group('requests');

arch('form requests are only used by controllers')
    ->expect('App\Http\Requests')->toOnlyBeUsedIn(['App\Http\Controllers'])
    ->group('requests');
