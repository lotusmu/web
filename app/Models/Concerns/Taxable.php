<?php

namespace App\Models\Concerns;

use App\Enums\Utility\OperationType;
use App\Models\Utility\GameServer;
use App\Models\Utility\Setting;
use Illuminate\Support\Facades\Cache;

trait Taxable
{
    protected array $operationSettings;

    protected float $taxRate;

    protected ?int $serverId = null;

    public OperationType $operationType = OperationType::TRANSFER;

    public function bootTaxable(): void
    {
        $this->initializeTaxable();
    }

    public function initializeTaxable(?int $serverId = null): void
    {
        $this->serverId = $serverId ?? $this->getCurrentServerId();
        $this->operationSettings = Setting::getGroup($this->operationType->value, $this->serverId);
        $this->taxRate = $this->getRate();
    }

    public function calculateRate(float $amount): float
    {
        return match ($this->operationType) {
            OperationType::TRANSFER, OperationType::EXCHANGE => round($amount * ($this->getRate() / 100)),
            OperationType::PK_CLEAR, OperationType::QUEST_SKIP => round($this->getCost() * $amount),
            default => 0,
        };
    }

    protected function getRate(): float
    {
        $path = match ($this->operationType) {
            OperationType::TRANSFER => 'transfer.rate',
            OperationType::EXCHANGE => 'exchange.rate',
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 0, $this->serverId) : 0;
    }

    protected function getCost(): int
    {
        $path = match ($this->operationType) {
            OperationType::STEALTH => 'stealth.cost',
            OperationType::PK_CLEAR => 'pk_clear.cost',
            OperationType::QUEST_SKIP => 'quest_skip.cost',
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 0, $this->serverId) : 0;
    }

    protected function getResourceType(): string
    {
        $path = match ($this->operationType) {
            OperationType::STEALTH => 'stealth.resource',
            OperationType::PK_CLEAR => 'pk_clear.resource',
            OperationType::QUEST_SKIP => 'quest_skip.resource',
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 'tokens', $this->serverId) : 'tokens';
    }

    protected function getDuration(): int
    {
        if ($this->operationType !== OperationType::STEALTH) {
            return 0;
        }

        return Setting::getValue($this->operationType->value, 'stealth.duration', 7, $this->serverId);
    }

    private function getCurrentServerId(): int
    {
        $connectionName = session('game_db_connection', 'gamedb_main');

        return Cache::remember("server_id_for_{$connectionName}", now()->addHours(1), function () use ($connectionName) {
            return GameServer::where('connection_name', $connectionName)->value('id') ?? 1;
        });
    }
}
