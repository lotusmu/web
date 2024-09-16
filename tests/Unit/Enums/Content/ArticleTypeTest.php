<?php

use App\Enums\Content\ArticleType;
use Filament\Support\Contracts\HasLabel;

it('has correct cases', function () {
    expect(ArticleType::cases())->toHaveCount(2)
        ->and(ArticleType::NEWS->value)->toBe('news')
        ->and(ArticleType::PATCH_NOTE->value)->toBe('patch_note');
});

it('returns correct label for each case', function () {
    expect(ArticleType::NEWS->getLabel())->toBe('News')
        ->and(ArticleType::PATCH_NOTE->getLabel())->toBe('Patch Note');
});

it('implements HasLabel interface', function () {
    expect(ArticleType::NEWS)->toBeInstanceOf(HasLabel::class)
        ->and(ArticleType::PATCH_NOTE)->toBeInstanceOf(HasLabel::class);
});

it('can be created from valid string values', function () {
    expect(ArticleType::from('news'))->toBe(ArticleType::NEWS)
        ->and(ArticleType::from('patch_note'))->toBe(ArticleType::PATCH_NOTE);
});

it('throws exception for invalid string value', function () {
    expect(fn () => ArticleType::from('invalid'))->toThrow(ValueError::class);
});
