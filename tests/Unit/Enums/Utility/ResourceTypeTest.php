<?php

use App\Enums\Utility\ResourceType;
use Filament\Support\Contracts\HasLabel;

it('returns correct labels', function () {
    expect(ResourceType::TOKENS->getLabel())->toBe('Tokens')
        ->and(ResourceType::CREDITS->getLabel())->toBe('Credits')
        ->and(ResourceType::ZEN->getLabel())->toBe('Zen');
});

it('has correct values', function () {
    expect(ResourceType::TOKENS->value)->toBe('tokens')
        ->and(ResourceType::CREDITS->value)->toBe('credits')
        ->and(ResourceType::ZEN->value)->toBe('zen');
});

it('implements HasLabel interface', function () {
    expect(ResourceType::TOKENS)
        ->toBeInstanceOf(HasLabel::class);
});
