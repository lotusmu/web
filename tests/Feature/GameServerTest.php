<?php

use App\Models\GameServer;

it('can be instantiated', function () {
    $gameServer = new GameServer;
    expect($gameServer)->toBeInstanceOf(GameServer::class);
});

it('has fillable attributes', function () {
    $fillable = [
        'name',
        'connection_name',
        'experience_rate',
        'drop_rate',
        'is_active',
    ];

    $gameServer = new GameServer;
    expect($gameServer->getFillable())->toBe($fillable);
});

it('can create a game server', function () {
    $data = [
        'name' => 'Test Server',
        'connection_name' => 'test_connection',
        'experience_rate' => 1.5,
        'drop_rate' => 2.0,
        'is_active' => true,
    ];

    $gameServer = GameServer::create($data);

    $this->assertDatabaseHas('game_servers', $data);
    expect($gameServer)->toBeInstanceOf(GameServer::class)
        ->and($gameServer->name)->toBe('Test Server')
        ->and($gameServer->connection_name)->toBe('test_connection')
        ->and($gameServer->experience_rate)->toBe(1.5)
        ->and($gameServer->drop_rate)->toBe(2.0)
        ->and($gameServer->is_active)->toBeTrue();
});

it('can update a game server', function () {
    $gameServer = GameServer::factory()->create();

    $updatedData = [
        'name' => 'Updated Server',
        'experience_rate' => 2.0,
    ];

    $gameServer->update($updatedData);

    $this->assertDatabaseHas('game_servers', $updatedData);
    expect($gameServer->fresh())
        ->name->toBe('Updated Server')
        ->experience_rate->toBe(2.0);
});
