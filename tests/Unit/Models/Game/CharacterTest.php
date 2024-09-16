<?php

use App\Enums\Game\CharacterClass;
use App\Enums\Game\Map;
use App\Enums\Game\PkLevel;
use App\Models\Game\Character;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    $this->character = new Character;
});

it('can be instantiated', function () {
    expect($this->character)->toBeInstanceOf(Character::class);
});

it('has correct table name', function () {
    expect($this->character->getTable())->toBe('Character');
});

it('uses Name as primary key', function () {
    expect($this->character->getKeyName())->toBe('Name');
});

it('has string primary key', function () {
    expect($this->character->getKeyType())->toBe('string');
});

it('does not use timestamps', function () {
    expect($this->character->usesTimestamps())->toBeFalse();
});

it('does not use auto-incrementing IDs', function () {
    expect($this->character->getIncrementing())->toBeFalse();
});

it('has correct fillable attributes', function () {
    $expectedFillable = [
        'AccountID', 'Name', 'cLevel', 'LevelUpPoint', 'Class', 'Strength',
        'Dexterity', 'Vitality', 'Energy', 'Leadership', 'Money', 'MapNumber',
        'MapPosX', 'MapPosY', 'PkCount', 'PkLevel', 'PkTime', 'CtlCode',
        'ResetCount', 'MasterResetCount', 'ExtInventory', 'Kills', 'Deads',
    ];
    expect($this->character->getFillable())->toBe($expectedFillable);
});

it('casts attributes correctly', function () {
    $casts = $this->character->getCasts();

    expect($casts['cLevel'])->toBe('integer')
        ->and($casts['Class'])->toBe(CharacterClass::class)
        ->and($casts['MapNumber'])->toBe(Map::class)
        ->and($casts['PkLevel'])->toBe(PkLevel::class);
});

it('can update a character', function () {
    $character = Character::factory()->create();

    $updatedData = [
        'cLevel' => 10,
        'Strength' => 100,
    ];

    $character->update($updatedData);

    $this->assertDatabaseHas('Character', $updatedData + ['Name' => $character->Name], 'gamedb_main');
    expect($character->fresh())
        ->cLevel->toBe(10)
        ->Strength->toBe(100);
});

it('belongs to a member', function () {
    $relation = $this->character->member();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Member::class)
        ->and($relation->getForeignKeyName())->toBe('AccountID')
        ->and($relation->getOwnerKeyName())->toBe('memb___id');
});
