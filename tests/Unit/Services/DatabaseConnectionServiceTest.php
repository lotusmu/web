<?php

use App\Models\GameServer;
use App\Services\DatabaseConnectionService;
use Illuminate\Support\Facades\Config;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    DB::beginTransaction();

    $this->testServer = GameServer::factory()->create([
        'connection_name' => 'test_connection',
    ]);
});

afterEach(function () {
    DB::rollBack();
});

test('setConnection sets the correct connection', function () {
    $result = DatabaseConnectionService::setConnection('test_connection');

    expect($result->id)->toBe($this->testServer->id)
        ->and(Config::get('database.connections.gamedb_main'))->toBe('test_connection');
    $this->assertDatabaseHas('game_servers', ['connection_name' => 'test_connection']);
});

test('setConnection throws exception for invalid connection name', function () {
    DatabaseConnectionService::setConnection('non_existent_connection');
})->throws(Illuminate\Database\Eloquent\ModelNotFoundException::class);
