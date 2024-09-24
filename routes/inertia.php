<?php

use Codetechnl\NovaAwsCloudwatch\Http\Controllers\InertiaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InertiaController::class, 'main']);
Route::get('/streams', [InertiaController::class, 'streams']);
Route::get('/stream', [InertiaController::class, 'stream']);
