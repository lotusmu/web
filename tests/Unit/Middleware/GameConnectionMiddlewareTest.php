<?php

use App\Http\Middleware\GameConnectionMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

it('sets game_db_connection to gamedb_main when session is empty', function () {
    $request = new Request;
    $middleware = new GameConnectionMiddleware;

    $response = $middleware->handle($request, function ($req) {
        return $req;
    });

    expect(session('game_db_connection'))->toBe('gamedb_main');
});

it('keeps existing game_db_connection when it exists in config', function () {
    Config::set('database.connections.gamedb_test', []);
    session(['game_db_connection' => 'gamedb_test']);

    $request = new Request;
    $middleware = new GameConnectionMiddleware;

    $response = $middleware->handle($request, function ($req) {
        return $req;
    });

    expect(session('game_db_connection'))->toBe('gamedb_test');
});

it('sets game_db_connection to gamedb_main when existing connection is not in config', function () {
    session(['game_db_connection' => 'non_existent_connection']);

    $request = new Request;
    $middleware = new GameConnectionMiddleware;

    $response = $middleware->handle($request, function ($req) {
        return $req;
    });

    expect(session('game_db_connection'))->toBe('gamedb_main');
});

it('calls the next middleware in the chain', function () {
    $called = false;
    $request = new Request;
    $middleware = new GameConnectionMiddleware;

    $response = $middleware->handle($request, function ($req) use (&$called) {
        $called = true;

        return $req;
    });

    expect($called)->toBeTrue();
});
