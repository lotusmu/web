<?php

namespace App\Models\Concerns;

use App\Enums\Utility\OperationType;
use App\Models\Utility\Tax;

trait Taxable
{
    protected float $taxRate;

    public OperationType $operationType = OperationType::TRANSFER;

    public function bootTaxable(): void
    {
        $this->initializeTaxable();
    }

    public function initializeTaxable(): void
    {
        $this->taxRate = Tax::getRateFor($this->operationType);
    }

    public function calculateTax(float $amount): float
    {
        if ($this->operationType === OperationType::PK_CLEAR) {
            return round($this->taxRate * $amount);
        }

        return round($amount * ($this->taxRate / 100));
    }

    public function calculateTotalWithTax(float $amount): float
    {
        $taxAmount = $this->calculateTax($amount);

        if ($this->operationType === OperationType::PK_CLEAR) {
            return $taxAmount;
        }

        return $amount + $taxAmount;
    }
}
