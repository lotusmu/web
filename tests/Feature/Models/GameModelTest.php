<?php

use App\Models\GameModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class TestGameModel extends GameModel
{
    protected $table = 'test_table';
}

beforeEach(function () {
    Session::flush();
});

it('uses the default connection when no server is selected', function () {
    $model = new TestGameModel;
    $connection = $model->getConnection();

    expect($connection->getName())->toBe('gamedb_main');
});

it('uses the selected server connection when available', function () {
    Config::set('database.connections.test_server', [
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'database' => 'test_db',
        'username' => 'test_user',
        'password' => 'test_pass',
    ]);

    Session::put('selected_server_connection', 'test_server');

    $model = new TestGameModel;
    $connection = $model->getConnection();

    expect($connection->getName())->toBe('test_server');
});

it('falls back to default connection if selected connection is not configured', function () {
    Session::put('selected_server_connection', 'non_existent_server');

    $model = new TestGameModel;
    $connection = $model->getConnection();

    expect($connection->getName())->toBe('gamedb_main');
});
