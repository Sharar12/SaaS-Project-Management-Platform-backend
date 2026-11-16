<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'OrbitFlow API',
        'status' => 'ok',
        'api' => '/api/v1/bundle',
    ]);
});
