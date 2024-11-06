<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum SettingKey: string implements HasLabel
{
    case STEALTH_COST = 'stealth.cost';
    case STEALTH_DURATION = 'stealth.duration';
    case STEALTH_RESOURCE = 'stealth.resource';

    case TRANSFER_RATE = 'transfer.rate';
    case EXCHANGE_RATE = 'exchange.rate';

    case PK_CLEAR_COST = 'pk_clear.cost';
    case PK_CLEAR_RESOURCE = 'pk_clear.resource';

    public function getLabel(): string
    {
        return match ($this) {
            self::STEALTH_COST => 'Stealth Mode Cost',
            self::STEALTH_DURATION => 'Stealth Mode Duration',
            self::STEALTH_RESOURCE => 'Stealth Mode Resource',
            self::TRANSFER_RATE => 'Transfer Rate',
            self::EXCHANGE_RATE => 'Exchange Rate',
            self::PK_CLEAR_COST => 'PK Clear Cost',
            self::PK_CLEAR_RESOURCE => 'PK Clear Resource',
        };
    }

    public function getGroup(): OperationType
    {
        return match ($this) {
            self::STEALTH_COST, self::STEALTH_DURATION, self::STEALTH_RESOURCE => OperationType::STEALTH,
            self::TRANSFER_RATE => OperationType::TRANSFER,
            self::EXCHANGE_RATE => OperationType::EXCHANGE,
            self::PK_CLEAR_COST, self::PK_CLEAR_RESOURCE => OperationType::PK_CLEAR,
        };
    }

    public function getType(): SettingType
    {
        return match ($this) {
            self::STEALTH_COST, self::PK_CLEAR_COST => SettingType::COST,
            self::TRANSFER_RATE, self::EXCHANGE_RATE => SettingType::PERCENTAGE,
            self::STEALTH_DURATION => SettingType::DURATION,
            self::STEALTH_RESOURCE, self::PK_CLEAR_RESOURCE => SettingType::RESOURCE,
        };
    }

    /**
     * Get related settings for the same group
     */
    public function getRelatedSettings(): array
    {
        return match ($this) {
            self::PK_CLEAR_COST => [self::PK_CLEAR_RESOURCE],
            self::PK_CLEAR_RESOURCE => [self::PK_CLEAR_COST],
            self::STEALTH_COST => [self::STEALTH_RESOURCE, self::STEALTH_DURATION],
            self::STEALTH_RESOURCE => [self::STEALTH_COST, self::STEALTH_DURATION],
            self::STEALTH_DURATION => [self::STEALTH_COST, self::STEALTH_RESOURCE],
            default => [],
        };
    }

    /**
     * Get all settings required for a complete feature
     */
    public static function getFeatureSettings(OperationType $operation): array
    {
        return match ($operation) {
            OperationType::PK_CLEAR => [self::PK_CLEAR_COST, self::PK_CLEAR_RESOURCE],
            OperationType::STEALTH => [self::STEALTH_COST, self::STEALTH_RESOURCE, self::STEALTH_DURATION],
            OperationType::TRANSFER => [self::TRANSFER_RATE],
            OperationType::EXCHANGE => [self::EXCHANGE_RATE],
        };
    }

    public static function forGroup(OperationType $group): array
    {
        return collect(self::cases())
            ->filter(fn (self $key) => $key->getGroup() === $group)
            ->mapWithKeys(fn (self $key) => [$key->value => $key->getLabel()])
            ->toArray();
    }
}
