<?php

declare(strict_types=1);
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

arch('models extend Eloquent')
    ->expect('App\Models')->toExtend(Model::class)
    ->group('models');

arch('models use factories')
    ->expect('App\Models')->toUseTrait(HasFactory::class)
    ->group('models');

arch('models do not use the DB facade or the request')
    ->expect('App\Models')->not->toUse([DB::class, Request::class])
    ->group('models');

arch('models are final-free plain classes without suffixes')
    ->expect('App\Models')->toBeClasses()->not->toHaveSuffix('Model')
    ->group('models');
