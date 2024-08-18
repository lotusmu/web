<?php

use App\Filament\Infolists\Components\Entry\CharacterClassEntry;
use Filament\Infolists\Components\Entry;

it('extends the Filament Entry class', function () {
    expect(new CharacterClassEntry('test'))->toBeInstanceOf(Entry::class);
});

it('uses the correct view', function () {
    $entry = new CharacterClassEntry('test');

    $reflection = new ReflectionClass($entry);
    $property = $reflection->getProperty('view');

    expect($property->getValue($entry))->toBe('filament.infolists.entries.character-class');
});

it('sets and gets image size', function () {
    $entry = new CharacterClassEntry('test');

    expect($entry->getImageSize())->toBe(64);

    $entry->imageSize(128);
    expect($entry->getImageSize())->toBe(128);
});

it('sets image size using a closure', function () {
    $entry = new CharacterClassEntry('test');

    $entry->imageSize(fn () => 256);
    expect($entry->getImageSize())->toBe(256);
});

describe('getState method', function () {
    it('throws an exception when trying to use non-existent from method', function () {
        $entry = new CharacterClassEntry('test');
        $entry->state(5);

        expect(fn () => $entry->getState())->toThrow(BadMethodCallException::class);
    });

    it('handles non-integer state', function () {
        $entry = new CharacterClassEntry('test');
        $state = new stdClass;
        $entry->state($state);

        expect($entry->getState())->toBe($state);
    });
});
