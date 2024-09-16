<?php

use App\Models\Game\Wallet;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');
    refreshTable('CashShopData', 'gamedb_main');
});

it('can create a credit instance', function () {
    $wallet = Wallet::factory()->create();

    expect($wallet)->toBeInstanceOf(Wallet::class);
});

it('has the correct table name', function () {
    $wallet = new Wallet;

    expect($wallet->getTable())->toBe('CashShopData');
});

it('uses the correct connection', function () {
    $wallet = new Wallet;

    expect($wallet->getConnectionName())->toBe('gamedb_main');
});

it('has a non-incrementing primary key', function () {
    $wallet = new Wallet;

    expect($wallet->incrementing)->toBeFalse();
});

it('has the correct primary key', function () {
    expect((new Wallet)->getKeyName())->toBe('AccountID');
});

it('has the correct fillable attributes', function () {
    $wallet = new Wallet;

    expect($wallet->getFillable())->toBe(['AccountID', 'WCoinC', 'zen']);
});

it('casts WCoinC and zen to integer', function () {
    $wallet = Wallet::factory()->create([
        'WCoinC' => '100',
        'zen' => '100',
    ]);

    expect($wallet->WCoinC)->toBeInt()
        ->and($wallet->zen)->toBeInt();
});

it('belongs to a member', function () {
    $wallet = new Wallet;
    $relation = $wallet->member();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Member::class)
        ->and($relation->getForeignKeyName())->toBe('memb___id')
        ->and($relation->getOwnerKeyName())->toBe('AccountID');
});
