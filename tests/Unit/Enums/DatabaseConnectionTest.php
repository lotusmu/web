<?php

use App\Enums\DatabaseConnection;
use Filament\Support\Contracts\HasLabel;

it('has the correct number of cases', function () {
    expect(DatabaseConnection::cases())->toHaveCount(2);
});

it('has the correct case values', function () {
    expect(DatabaseConnection::GAME_DB_MAIN->value)->toBe('gamedb_main')
        ->and(DatabaseConnection::GAME_DB_X200->value)->toBe('gamedb_x200');
});

it('implements HasLabel interface', function () {
    expect(DatabaseConnection::GAME_DB_MAIN)->toBeInstanceOf(HasLabel::class);
});

it('returns correct label for each case', function () {
    expect(DatabaseConnection::GAME_DB_MAIN->getLabel())->toBe('Main')
        ->and(DatabaseConnection::GAME_DB_X200->getLabel())->toBe('x200');
});

it('returns correct options array', function () {
    $expectedOptions = [
        'gamedb_main' => 'Main',
        'gamedb_x200' => 'x200',
    ];

    expect(DatabaseConnection::getOptions())->toBe($expectedOptions);
});

it('throws exception for invalid value', function () {
    expect(fn () => DatabaseConnection::from('invalid_value'))
        ->toThrow(ValueError::class, '"invalid_value" is not a valid backing value for enum App\Enums\DatabaseConnection');
});
