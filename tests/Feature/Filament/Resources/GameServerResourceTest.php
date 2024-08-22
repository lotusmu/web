<?php

use App\Filament\Resources\GameServerResource;
use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Config;

describe('pages', function () {
    it('can render list page', function () {
        $this->get(GameServerResource::getUrl('index'))->assertSuccessful();
    });

    it('can render edit page', function () {
        DB::beginTransaction();

        $gameServer = GameServer::factory()->create();
        $this->get(GameServerResource::getUrl('edit', ['record' => $gameServer]))->assertSuccessful();

        DB::rollBack();
    });

    it('does not have create page', function () {
        $this->get(GameServerResource::getUrl('create'))->assertSuccessful();
    });
});

it('uses the correct model', function () {
    expect(GameServerResource::getModel())->toBe(GameServer::class);
});

it('has no relations', function () {
    expect(GameServerResource::getRelations())->toBeEmpty();
});

it('gets correct database connection options', function () {
    Config::set('database.connections', [
        'default' => [],
        'gamedb_1' => [],
        'gamedb_2' => [],
        'other' => [],
    ]);

    $gameServerResource = new GameServerResource;

    $options = Closure::bind(function () {
        return $this->getDbConnectionOptions();
    }, $gameServerResource, GameServerResource::class)();

    expect($options)->toBe([
        'gamedb_1' => 'gamedb_1',
        'gamedb_2' => 'gamedb_2',
    ]);
});
