<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

Route::get('/docker-test', function () {
    return response()->json([
        'message' => 'Laravel is running with FrankenPHP!',
        'php_sapi' => php_sapi_name(),
        'database' => DB::connection()->getPdo() ? 'Connected' : 'Disconnected',
        'cache' => Cache::store('redis')->put('test', 'working', 60) ? 'Redis working' : 'Redis failed',
        'timestamp' => now()->toISOString(),
    ]);
});
Route::get('/', function () {
    return view('welcome');
});
