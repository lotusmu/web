<?php

use App\Enums\Ticket\TicketStatus;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

it('has the correct cases', function () {
    expect(TicketStatus::cases())->toHaveCount(6)
        ->and(TicketStatus::NEW->value)->toBe('new')
        ->and(TicketStatus::IN_PROGRESS->value)->toBe('in_progress')
        ->and(TicketStatus::ON_HOLD->value)->toBe('on_hold')
        ->and(TicketStatus::PENDING->value)->toBe('pending')
        ->and(TicketStatus::RESOLVED->value)->toBe('resolved')
        ->and(TicketStatus::CLOSED->value)->toBe('closed');
});

it('returns the correct label for each status', function () {
    expect(TicketStatus::NEW->getLabel())->toBe('New')
        ->and(TicketStatus::IN_PROGRESS->getLabel())->toBe('In Progress')
        ->and(TicketStatus::ON_HOLD->getLabel())->toBe('On Hold')
        ->and(TicketStatus::PENDING->getLabel())->toBe('Pending')
        ->and(TicketStatus::RESOLVED->getLabel())->toBe('Resolved')
        ->and(TicketStatus::CLOSED->getLabel())->toBe('Closed');
});

it('returns the correct getColor for each status', function () {
    expect(TicketStatus::NEW->getColor())->toBe(Color::Emerald)
        ->and(TicketStatus::IN_PROGRESS->getColor())->toBe(Color::Blue)
        ->and(TicketStatus::ON_HOLD->getColor())->toBe(Color::Amber)
        ->and(TicketStatus::PENDING->getColor())->toBe(Color::Orange)
        ->and(TicketStatus::RESOLVED->getColor())->toBe(Color::Teal)
        ->and(TicketStatus::CLOSED->getColor())->toBe(Color::Zinc);
});

it('returns the correct color for each status', function () {
    expect(TicketStatus::NEW->color())->toBe('emerald')
        ->and(TicketStatus::IN_PROGRESS->color())->toBe('blue')
        ->and(TicketStatus::ON_HOLD->color())->toBe('amber')
        ->and(TicketStatus::PENDING->color())->toBe('orange')
        ->and(TicketStatus::RESOLVED->color())->toBe('teal')
        ->and(TicketStatus::CLOSED->color())->toBe('zinc');
});

it('returns the correct icon for each status with getIcon method', function () {
    expect(TicketStatus::NEW->getIcon())->toBe('heroicon-o-plus-circle')
        ->and(TicketStatus::IN_PROGRESS->getIcon())->toBe('heroicon-o-play')
        ->and(TicketStatus::ON_HOLD->getIcon())->toBe('heroicon-o-pause')
        ->and(TicketStatus::PENDING->getIcon())->toBe('heroicon-o-clock')
        ->and(TicketStatus::RESOLVED->getIcon())->toBe('heroicon-o-check-circle')
        ->and(TicketStatus::CLOSED->getIcon())->toBe('heroicon-o-x-circle');
});

it('returns the correct icon for each status with icon method', function () {
    expect(TicketStatus::NEW->icon())->toBe('plus-circle')
        ->and(TicketStatus::IN_PROGRESS->icon())->toBe('play')
        ->and(TicketStatus::ON_HOLD->icon())->toBe('pause')
        ->and(TicketStatus::PENDING->icon())->toBe('clock')
        ->and(TicketStatus::RESOLVED->icon())->toBe('check-circle')
        ->and(TicketStatus::CLOSED->icon())->toBe('x-circle');
});

it('implements HasLabel interface', function () {
    expect(TicketStatus::NEW)->toBeInstanceOf(HasLabel::class);
});

it('implements HasColor interface', function () {
    expect(TicketStatus::NEW)->toBeInstanceOf(HasColor::class);
});

it('implements HasIcon interface', function () {
    expect(TicketStatus::NEW)->toBeInstanceOf(HasIcon::class);
});

it('can be instantiated from string value', function () {
    expect(TicketStatus::from('new'))->toBe(TicketStatus::NEW)
        ->and(TicketStatus::from('in_progress'))->toBe(TicketStatus::IN_PROGRESS)
        ->and(TicketStatus::from('on_hold'))->toBe(TicketStatus::ON_HOLD)
        ->and(TicketStatus::from('pending'))->toBe(TicketStatus::PENDING)
        ->and(TicketStatus::from('resolved'))->toBe(TicketStatus::RESOLVED)
        ->and(TicketStatus::from('closed'))->toBe(TicketStatus::CLOSED);
});

it('throws exception for invalid string value', function () {
    TicketStatus::from('invalid');
})->throws(ValueError::class);
