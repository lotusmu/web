<?php

use App\Enums\Utility\ActivityType;

it('returns correct labels', function () {
    expect(ActivityType::INCREMENT->getLabel())->toBe('Increment')
        ->and(ActivityType::DECREMENT->getLabel())->toBe('Decrement')
        ->and(ActivityType::INTERNAL->getLabel())->toBe('Internal')
        ->and(ActivityType::DEFAULT->getLabel())->toBe('Default');
});

it('returns correct colors', function () {
    expect(ActivityType::INCREMENT->getColor())->toBe('green')
        ->and(ActivityType::DECREMENT->getColor())->toBe('red')
        ->and(ActivityType::INTERNAL->getColor())->toBe('yellow')
        ->and(ActivityType::DEFAULT->getColor())->toBe('zinc');
});

it('returns correct icons', function () {
    expect(ActivityType::INCREMENT->getIcon())->toBe('arrow-up')
        ->and(ActivityType::DECREMENT->getIcon())->toBe('arrow-down')
        ->and(ActivityType::INTERNAL->getIcon())->toBe('arrows-right-left')
        ->and(ActivityType::DEFAULT->getIcon())->toBe('document-text');
});

it('has correct values', function () {
    expect(ActivityType::INCREMENT->value)->toBe('increment')
        ->and(ActivityType::DECREMENT->value)->toBe('decrement')
        ->and(ActivityType::INTERNAL->value)->toBe('internal')
        ->and(ActivityType::DEFAULT->value)->toBe('default');
});
