<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

 Route::get('/', [\Codetechnl\NovaAwsCloudwatch\Http\Controllers\IndexController::class, 'index']);
 Route::get('/streams', [\Codetechnl\NovaAwsCloudwatch\Http\Controllers\IndexController::class, 'showStreams']);
 Route::get('/stream', [\Codetechnl\NovaAwsCloudwatch\Http\Controllers\IndexController::class, 'showStream']);
