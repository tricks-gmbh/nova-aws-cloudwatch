<?php

use Codetechnl\NovaAwsCloudwatch\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index']);
Route::get('/streams', [IndexController::class, 'showStreams']);
Route::get('/stream', [IndexController::class, 'showStream']);
