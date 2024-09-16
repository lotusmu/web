<?php

use App\Enums\Game\ScheduledEventType;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

it('has correct cases', function () {
    expect(ScheduledEventType::cases())->toHaveCount(2)
        ->and(ScheduledEventType::EVENT->value)->toBe('event')
        ->and(ScheduledEventType::INVASION->value)->toBe('invasion');
});

it('returns correct label for each case', function () {
    expect(ScheduledEventType::EVENT->getLabel())->toBe('Event')
        ->and(ScheduledEventType::INVASION->getLabel())->toBe('Invasion');
});

it('returns correct color for each case', function () {
    expect(ScheduledEventType::EVENT->getColor())->toBe(Color::Sky)
        ->and(ScheduledEventType::INVASION->getColor())->toBe(Color::Pink);
});

it('implements HasLabel interface', function () {
    expect(ScheduledEventType::EVENT)->toBeInstanceOf(HasLabel::class)
        ->and(ScheduledEventType::INVASION)->toBeInstanceOf(HasLabel::class);
});

it('implements HasColor interface', function () {
    expect(ScheduledEventType::EVENT)->toBeInstanceOf(HasColor::class)
        ->and(ScheduledEventType::INVASION)->toBeInstanceOf(HasColor::class);
});
