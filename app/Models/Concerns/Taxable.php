<?php

namespace App\Models\Concerns;

use App\Enums\Utility\OperationType;
use App\Models\Utility\Setting;

trait Taxable
{
    protected array $operationSettings;

    protected float $taxRate;

    public OperationType $operationType = OperationType::TRANSFER;

    public function bootTaxable(): void
    {
        $this->initializeTaxable();
    }

    public function initializeTaxable(): void
    {
        $this->operationSettings = Setting::getGroup($this->operationType->value);
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

        return $path ? Setting::getValue($this->operationType->value, $path, 0) : 0;
    }

    protected function getCost(): int
    {
        $path = match ($this->operationType) {
            OperationType::STEALTH => 'stealth.cost',
            OperationType::PK_CLEAR => 'pk_clear.cost',
            OperationType::QUEST_SKIP => 'quest_skip.cost',
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 0) : 0;
    }

    protected function getResourceType(): string
    {
        $path = match ($this->operationType) {
            OperationType::STEALTH => 'stealth.resource',
            OperationType::PK_CLEAR => 'pk_clear.resource',
            OperationType::QUEST_SKIP => 'quest_skip.resource',
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 'tokens') : 'tokens';
    }

    protected function getDuration(): int
    {
        if ($this->operationType !== OperationType::STEALTH) {
            return 0;
        }

        return Setting::getValue($this->operationType->value, 'stealth.duration', 7);
    }
}
