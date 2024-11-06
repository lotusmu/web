<?php

namespace App\Support\Settings;

use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Exceptions\Settings\MissingSettingsException;
use ValueError;

class SettingsValidator
{
    public function validate(string $group, array $settings): void
    {
        match ($group) {
            OperationType::STEALTH->value => $this->validateStealthSettings($settings),
            OperationType::TRANSFER->value => $this->validateTransferSettings($settings),
            OperationType::EXCHANGE->value => $this->validateExchangeSettings($settings),
            OperationType::PK_CLEAR->value => $this->validatePkClearSettings($settings),
            default => throw new MissingSettingsException("Unknown operation type: {$group}")
        };
    }

    private function validateStealthSettings(array $settings): void
    {
        $this->ensureKeysExist($settings, 'stealth', ['cost', 'duration', 'resource']);

        $this->validateCost($settings['stealth']['cost']);
        $this->validateDuration($settings['stealth']['duration']);
        $this->validateResource($settings['stealth']['resource']);
    }

    private function validateTransferSettings(array $settings): void
    {
        $this->ensureKeysExist($settings, 'transfer', ['rate']);

        $this->validateRate($settings['transfer']['rate']);
    }

    private function validateExchangeSettings(array $settings): void
    {
        $this->ensureKeysExist($settings, 'exchange', ['rate']);

        $this->validateRate($settings['exchange']['rate']);
    }

    private function validatePkClearSettings(array $settings): void
    {
        $this->ensureKeysExist($settings, 'pk_clear', ['cost', 'resource']);

        $this->validateCost($settings['pk_clear']['cost']);
        $this->validateResource($settings['pk_clear']['resource']);
    }

    private function ensureKeysExist(array $settings, string $group, array $required): void
    {
        if (! isset($settings[$group])) {
            throw new MissingSettingsException("Missing {$group} settings group");
        }

        $missing = array_diff($required, array_keys($settings[$group]));

        if (! empty($missing)) {
            throw new MissingSettingsException(
                "Missing required settings for {$group}: ".implode(', ', $missing)
            );
        }
    }

    private function validateCost(mixed $value): void
    {
        if (! is_numeric($value) || $value < 0) {
            throw new MissingSettingsException('Cost must be a positive number');
        }
    }

    private function validateDuration(mixed $value): void
    {
        if (! is_numeric($value) || $value < 1) {
            throw new MissingSettingsException('Duration must be at least 1 day');
        }
    }

    private function validateRate(mixed $value): void
    {
        if (! is_numeric($value) || $value < 0 || $value > 100) {
            throw new MissingSettingsException('Rate must be between 0 and 100');
        }
    }

    private function validateResource(mixed $value): void
    {
        try {
            ResourceType::from($value);
        } catch (ValueError $e) {
            throw new MissingSettingsException('Invalid resource type');
        }
    }
}
