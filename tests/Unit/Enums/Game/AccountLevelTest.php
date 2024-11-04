<?php

use App\Enums\Game\AccountLevel;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

it('has the correct enum values', function () {
    expect(AccountLevel::cases())->toHaveCount(4)
        ->and(AccountLevel::Regular->value)->toBe(0)
        ->and(AccountLevel::Bronze->value)->toBe(1)
        ->and(AccountLevel::Silver->value)->toBe(2)
        ->and(AccountLevel::Gold->value)->toBe(3);
});

it('implements necessary interfaces', function () {
    $interfaces = class_implements(AccountLevel::class);
    expect($interfaces)->toContain(HasColor::class)
        ->and($interfaces)->toContain(HasIcon::class)
        ->and($interfaces)->toContain(HasLabel::class);
});

it('returns the correct label for each level', function () {
    expect(AccountLevel::Regular->getLabel())->toBe('Regular')
        ->and(AccountLevel::Bronze->getLabel())->toBe('Bronze')
        ->and(AccountLevel::Silver->getLabel())->toBe('Silver')
        ->and(AccountLevel::Gold->getLabel())->toBe('Gold');
});

it('returns the correct color for each level', function () {
    expect(AccountLevel::Regular->getColor())->toBeNull()
        ->and(AccountLevel::Bronze->getColor())->toBe(Color::Orange)
        ->and(AccountLevel::Silver->getColor())->toBe(Color::Zinc)
        ->and(AccountLevel::Gold->getColor())->toBe(Color::Yellow)
        ->and(AccountLevel::Regular->badgeColor())->toBeNull()
        ->and(AccountLevel::Bronze->badgeColor())->toBe('orange')
        ->and(AccountLevel::Silver->badgeColor())->toBe('zinc')
        ->and(AccountLevel::Gold->badgeColor())->toBe('yellow');
});

it('returns the correct icon for each level', function () {
    expect(AccountLevel::Regular->getIcon())->toBeNull()
        ->and(AccountLevel::Bronze->getIcon())->toBe('heroicon-o-fire')
        ->and(AccountLevel::Silver->getIcon())->toBe('heroicon-o-fire')
        ->and(AccountLevel::Gold->getIcon())->toBe('heroicon-o-fire');
});
