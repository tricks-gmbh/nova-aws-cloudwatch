<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Tricks\NovaAwsCloudwatch\Http\Controllers\InertiaController;
use Tricks\NovaAwsCloudwatch\Http\Middleware\Authorize;

Nova::router(['nova', Authenticate::class, Authorize::class], 'nova-aws-cloudwatch')
    ->group(fn () => [
        Route::get('/', [InertiaController::class, 'index']),
        Route::get('/groups/{group}', [InertiaController::class, 'showGroup']),
        Route::get('/groups/{group}/streams/{stream}', [InertiaController::class, 'showStream']),
    ]);
