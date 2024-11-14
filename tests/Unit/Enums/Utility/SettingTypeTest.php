<?php

use App\Enums\Utility\SettingType;
use Filament\Support\Contracts\HasLabel;

it('returns correct labels', function () {
    expect(SettingType::COST->getLabel())->toBe('Flat Cost')
        ->and(SettingType::PERCENTAGE->getLabel())->toBe('Percentage Rate')
        ->and(SettingType::DURATION->getLabel())->toBe('Duration (Days)')
        ->and(SettingType::RESOURCE->getLabel())->toBe('Resource Type');
});

it('correctly determines if value is needed', function () {
    expect(SettingType::COST->needsValue())->toBeTrue()
        ->and(SettingType::PERCENTAGE->needsValue())->toBeTrue()
        ->and(SettingType::DURATION->needsValue())->toBeTrue()
        ->and(SettingType::RESOURCE->needsValue())->toBeFalse();
});

it('has correct values', function () {
    expect(SettingType::COST->value)->toBe('cost')
        ->and(SettingType::PERCENTAGE->value)->toBe('percentage')
        ->and(SettingType::DURATION->value)->toBe('duration')
        ->and(SettingType::RESOURCE->value)->toBe('resource');
});

it('implements HasLabel interface', function () {
    expect(SettingType::COST)->toBeInstanceOf(HasLabel::class);
});
