<?php

use App\Models\Utility\GameServer;
use App\Services\DatabaseConnectionService;
use Illuminate\Support\Facades\Config;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->connectionName = fake()->unique()->word;

    $this->testServer = GameServer::factory()->create([
        'connection_name' => $this->connectionName,
    ]);
});

test('setConnection sets the correct connection', function () {
    $result = DatabaseConnectionService::setConnection($this->connectionName);

    expect($result->id)->toBe($this->testServer->id)
        ->and(Config::get('database.connections.gamedb_main'))->toBe($this->connectionName);
    $this->assertDatabaseHas('game_servers', ['connection_name' => $this->connectionName]);
});

test('setConnection throws exception for invalid connection name', function () {
    DatabaseConnectionService::setConnection('non_existent_connection');
})->throws(Illuminate\Database\Eloquent\ModelNotFoundException::class);
