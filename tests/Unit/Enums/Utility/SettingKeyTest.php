<?php

use App\Enums\Utility\OperationType;
use App\Enums\Utility\SettingKey;
use App\Enums\Utility\SettingType;
use Filament\Support\Contracts\HasLabel;

it('returns correct labels', function () {
    expect(SettingKey::STEALTH_COST->getLabel())->toBe('Stealth Mode Cost')
        ->and(SettingKey::EXCHANGE_RATE->getLabel())->toBe('Exchange Rate')
        ->and(SettingKey::PK_CLEAR_RESOURCE->getLabel())->toBe('PK Clear Resource');
});

it('returns correct operation type groups', function () {
    expect(SettingKey::STEALTH_COST->getGroup())->toBe(OperationType::STEALTH)
        ->and(SettingKey::TRANSFER_RATE->getGroup())->toBe(OperationType::TRANSFER)
        ->and(SettingKey::PK_CLEAR_COST->getGroup())->toBe(OperationType::PK_CLEAR);
});

it('returns correct setting types', function () {
    expect(SettingKey::STEALTH_COST->getType())->toBe(SettingType::COST)
        ->and(SettingKey::TRANSFER_RATE->getType())->toBe(SettingType::PERCENTAGE)
        ->and(SettingKey::STEALTH_DURATION->getType())->toBe(SettingType::DURATION)
        ->and(SettingKey::PK_CLEAR_RESOURCE->getType())->toBe(SettingType::RESOURCE);
});

it('returns correct related settings', function () {
    expect(SettingKey::PK_CLEAR_COST->getRelatedSettings())->toBe([SettingKey::PK_CLEAR_RESOURCE])
        ->and(SettingKey::STEALTH_COST->getRelatedSettings())->toBe([SettingKey::STEALTH_RESOURCE, SettingKey::STEALTH_DURATION])
        ->and(SettingKey::TRANSFER_RATE->getRelatedSettings())->toBe([]);
});

it('returns correct feature settings', function () {
    expect(SettingKey::getFeatureSettings(OperationType::PK_CLEAR))
        ->toBe([SettingKey::PK_CLEAR_COST, SettingKey::PK_CLEAR_RESOURCE])
        ->and(SettingKey::getFeatureSettings(OperationType::STEALTH))
        ->toBe([SettingKey::STEALTH_COST, SettingKey::STEALTH_RESOURCE, SettingKey::STEALTH_DURATION])
        ->and(SettingKey::getFeatureSettings(OperationType::TRANSFER))->toBe([SettingKey::TRANSFER_RATE])
        ->and(SettingKey::getFeatureSettings(OperationType::EXCHANGE))->toBe([SettingKey::EXCHANGE_RATE]);
});

it('returns settings for specific group', function () {
    $pkClearSettings = SettingKey::forGroup(OperationType::PK_CLEAR);

    expect($pkClearSettings)->toBe([
        'pk_clear.cost' => 'PK Clear Cost',
        'pk_clear.resource' => 'PK Clear Resource',
    ]);
});

it('implements HasLabel interface', function () {
    expect(SettingKey::STEALTH_COST)->toBeInstanceOf(HasLabel::class);
});

it('has correct values', function () {
    expect(SettingKey::STEALTH_COST->value)->toBe('stealth.cost')
        ->and(SettingKey::PK_CLEAR_RESOURCE->value)->toBe('pk_clear.resource')
        ->and(SettingKey::TRANSFER_RATE->value)->toBe('transfer.rate');
});
