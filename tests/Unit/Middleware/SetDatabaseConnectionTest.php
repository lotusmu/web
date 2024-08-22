<?php

use App\Http\Middleware\SetDatabaseConnection;
use App\Models\Utility\GameServer;
use App\Services\DatabaseConnectionService;
use Illuminate\Http\Request;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->middleware = new SetDatabaseConnection;
});

test('middleware sets connection when connection name is present', function () {
    $this->testServer = GameServer::factory()->create([
        'connection_name' => 'test_connection',
    ]);

    session(['selected_server_connection' => 'test_connection']);

    $request = Request::create('/');

    $next = fn ($request) => response('OK');

    $response = $this->middleware->handle($request, $next);

    expect(Config::get('database.connections.gamedb_main'))->toBe('test_connection')
        ->and($response->getContent())->toBe('OK');
});

test('middleware does not set connection when connection name is absent', function () {
    $request = Request::create('/');

    $next = fn ($request) => response('OK');

    $spy = $this->spy(DatabaseConnectionService::class);

    $response = $this->middleware->handle($request, $next);

    $spy->shouldNotHaveReceived('setConnection');
    expect($response->getContent())->toBe('OK');
});
