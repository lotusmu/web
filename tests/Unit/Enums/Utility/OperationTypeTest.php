<?php

use App\Enums\Utility\OperationType;
use Filament\Support\Contracts\HasLabel;

it('returns correct labels', function () {
    expect(OperationType::TRANSFER->getLabel())->toBe('Transfer')
        ->and(OperationType::EXCHANGE->getLabel())->toBe('Exchange')
        ->and(OperationType::PK_CLEAR->getLabel())->toBe('PK Clear')
        ->and(OperationType::STEALTH->getLabel())->toBe('Stealth Mode');
});

it('has correct values', function () {
    expect(OperationType::TRANSFER->value)->toBe('transfer')
        ->and(OperationType::EXCHANGE->value)->toBe('exchange')
        ->and(OperationType::PK_CLEAR->value)->toBe('pk_clear')
        ->and(OperationType::STEALTH->value)->toBe('stealth');
});

it('implements HasLabel interface', function () {
    expect(OperationType::TRANSFER)
        ->toBeInstanceOf(HasLabel::class);
});
