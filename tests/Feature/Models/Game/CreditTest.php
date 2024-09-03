<?php

use App\Models\Game\Wallet;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');
    refreshTable('CashShopData', 'gamedb_main');
});

it('can create a credit instance', function () {
    $credit = Wallet::factory()->create();

    expect($credit)->toBeInstanceOf(Wallet::class);
});

it('has the correct table name', function () {
    $credit = new Wallet;
    expect($credit->getTable())->toBe('CashShopData');
});

it('uses the correct connection', function () {
    $credit = new Wallet;

    expect($credit->getConnectionName())->toBe('gamedb_main');
});

it('has a non-incrementing primary key', function () {
    $credit = new Wallet;

    expect($credit->incrementing)->toBeFalse();
});

it('has the correct primary key', function () {
    expect((new Wallet)->getKeyName())->toBe('AccountID');
});

it('has the correct fillable attributes', function () {
    $credit = new Wallet;

    expect($credit->getFillable())->toBe(['AccountID', 'WCoinC']);
});

it('casts WCoinC to integer', function () {
    $credit = Wallet::factory()->create(['WCoinC' => '100']);

    expect($credit->WCoinC)->toBeInt();
});

it('belongs to a member', function () {
    $credit = new Wallet;
    $relation = $credit->member();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Member::class)
        ->and($relation->getForeignKeyName())->toBe('memb___id')
        ->and($relation->getOwnerKeyName())->toBe('AccountID');
});
