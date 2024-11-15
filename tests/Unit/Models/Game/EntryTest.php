<?php

use App\Models\Game\Character;
use App\Models\Game\Entry;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    $this->entry = new Entry;
});

it('has correct table name', function () {
    expect($this->entry->getTable())->toBe('EventEntryCount');
});

it('does not use auto-incrementing IDs', function () {
    expect($this->entry->getIncrementing())->toBeFalse();
});

it('does not use timestamps', function () {
    expect($this->entry->usesTimestamps())->toBeFalse();
});

it('casts attributes correctly', function () {
    $casts = $this->entry->getCasts();

    expect($casts)
        ->toHaveKey('Name', 'string')
        ->toHaveKey('Type', 'integer')
        ->toHaveKey('EntryCount', 'integer');
});

it('belongs to a character', function () {
    $relation = $this->entry->character();

    expect($relation)
        ->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Character::class)
        ->and($relation->getForeignKeyName())->toBe('Name')
        ->and($relation->getOwnerKeyName())->toBe('Name');
});
