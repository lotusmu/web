<?php

use App\Enums\Ticket\TicketPriority;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

it('has the correct cases', function () {
    expect(TicketPriority::cases())->toHaveCount(3)
        ->and(TicketPriority::LOW->value)->toBe('low')
        ->and(TicketPriority::MEDIUM->value)->toBe('medium')
        ->and(TicketPriority::HIGH->value)->toBe('high');
});

it('returns the correct label for each priority', function () {
    expect(TicketPriority::LOW->getLabel())->toBe('Low')
        ->and(TicketPriority::MEDIUM->getLabel())->toBe('Medium')
        ->and(TicketPriority::HIGH->getLabel())->toBe('High');
});

it('returns the correct color for each priority', function () {
    expect(TicketPriority::LOW->getColor())->toBe(Color::Green)
        ->and(TicketPriority::MEDIUM->getColor())->toBe(Color::Yellow)
        ->and(TicketPriority::HIGH->getColor())->toBe(Color::Red);
});

it('implements HasLabel interface', function () {
    expect(TicketPriority::LOW)->toBeInstanceOf(HasLabel::class);
});

it('implements HasColor interface', function () {
    expect(TicketPriority::LOW)->toBeInstanceOf(HasColor::class);
});

it('can be instantiated from string value', function () {
    expect(TicketPriority::from('low'))->toBe(TicketPriority::LOW)
        ->and(TicketPriority::from('medium'))->toBe(TicketPriority::MEDIUM)
        ->and(TicketPriority::from('high'))->toBe(TicketPriority::HIGH);
});

it('throws exception for invalid string value', function () {
    TicketPriority::from('invalid');
})->throws(ValueError::class);
