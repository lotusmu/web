<?php

use App\Models\Game\Wallet;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');
    refreshTable('CashShopData', 'gamedb_main');
});

it('can create a wallet instance', function () {
    $user = User::factory()->create();
    $wallet = Wallet::factory()
        ->withExistingUser($user)
        ->make();

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
    $user = User::factory()->create();
    $wallet = Wallet::where('AccountID', $user->name)->first();

    $wallet->update([
        'WCoinC' => '100',
        'zen' => '100',
    ]);

    $wallet = $wallet->fresh();

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
