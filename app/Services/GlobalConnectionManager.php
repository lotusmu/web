<?php

namespace App\Services;

use App\Enums\DatabaseConnection;

class GlobalConnectionManager
{
    private static $instance = null;

    private $currentConnection = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function setConnection(string $connection)
    {
        $this->currentConnection = DatabaseConnection::from($connection);
    }

    public function getConnection(): ?string
    {
        return $this->currentConnection?->value;
    }
}
