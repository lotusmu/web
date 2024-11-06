<?php

namespace App\Models\Concerns;

use App\Enums\Utility\OperationType;
use App\Exceptions\Settings\MissingSettingsException;
use App\Models\Utility\Setting;
use Flux\Flux;

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
        try {
            $this->operationSettings = Setting::getGroup($this->operationType->value);
            $this->taxRate = $this->getRate();
        } catch (MissingSettingsException $e) {
            $this->handleMissingSettings();
        }
    }

    public function calculateRate(float $amount): float
    {
        return match ($this->operationType) {
            OperationType::TRANSFER, OperationType::EXCHANGE => round($amount * ($this->getRate() / 100)),
            OperationType::PK_CLEAR => round($this->getCost() * $amount),
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
            default => null,
        };

        return $path ? Setting::getValue($this->operationType->value, $path, 0) : 0;
    }

    protected function getResourceType(): string
    {
        $path = match ($this->operationType) {
            OperationType::STEALTH => 'stealth.resource',
            OperationType::PK_CLEAR => 'pk_clear.resource',
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

    protected function handleMissingSettings(): void
    {
        Flux::toast(
            text: __('This feature is not properly configured. Please contact an administrator.'),
            heading: __('Configuration Error'),
            variant: 'danger'
        );

        // Provide safe default values
        $this->operationSettings = $this->getDefaultSettings();
    }

    protected function getDefaultSettings(): array
    {
        return match ($this->operationType) {
            OperationType::STEALTH => [
                'stealth' => [
                    'cost' => 0,
                    'duration' => 1,
                    'currency' => 'tokens',
                ],
            ],
            OperationType::TRANSFER => [
                'transfer' => ['rate' => 0],
            ],
            OperationType::EXCHANGE => [
                'exchange' => ['rate' => 0],
            ],
            OperationType::PK_CLEAR => [
                'pk_clear' => [
                    'cost' => 0,
                    'currency' => 'zen',
                ],
            ],
        };
    }
}
