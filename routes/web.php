<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    try {
        DB::connection('game_server_1')->getPdo();
        return 'Connection to second database successful!';
    } catch (\Exception $e) {
        return 'Connection to second database failed: ' . $e->getMessage();
    }
});
