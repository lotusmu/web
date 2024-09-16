<?php

use App\Models\Game\Wallet;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');
});

it('uses the correct database connection and table name', function () {
    $member = new Member;
    expect($member->getConnectionName())->toBe('gamedb_main')
        ->and($member->getTable())->toBe('MEMB_INFO');
});

it('has the correct primary key configuration', function () {
    $member = new Member;
    expect($member->getKeyName())->toBe('memb___id')
        ->and($member->getKeyType())->toBe('string')
        ->and($member->incrementing)->toBeFalse();
});

it('does not use timestamps', function () {
    expect((new Member)->timestamps)->toBeFalse();
});

it('has the correct fillable attributes', function () {
    $expectedFillable = [
        'memb___id',
        'memb__pwd',
        'memb_name',
        'sno__numb',
        'mail_addr',
        'appl_days',
        'mail_chek',
        'bloc_code',
        'ctl1_code',
        'AccountLevel',
        'AccountExpireDate',
        'tokens',
    ];

    expect((new Member)->getFillable())->toContain(...$expectedFillable);
});

it('has working attribute accessors', function () {
    // Test setting attributes via constructor
    $initialUsername = fakeUsername();
    $initialEmail = fakeEmail();
    $initialPassword = fakePassword();

    $member = new Member([
        'memb___id' => $initialUsername,
        'mail_addr' => $initialEmail,
        'memb__pwd' => $initialPassword,
    ]);

    expect($member->name)->toBe($initialUsername)
        ->and($member->email)->toBe($initialEmail)
        ->and($member->password)->toBe($initialPassword);

    // Test setting attributes via accessors
    $newUsername = fakeUsername();
    $newEmail = fakeEmail();
    $newPassword = fakePassword();

    $member->name = $newUsername;
    $member->email = $newEmail;
    $member->password = $newPassword;

    expect($member->name)->toBe($newUsername)
        ->and($member->memb___id)->toBe($newUsername)
        ->and($member->email)->toBe($newEmail)
        ->and($member->mail_addr)->toBe($newEmail)
        ->and($member->password)->toBe($newPassword)
        ->and($member->memb__pwd)->toBe($newPassword);
});

it('belongs to a user', function () {
    $member = new Member;
    $relation = $member->user();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(User::class)
        ->and($relation->getForeignKeyName())->toBe('name')
        ->and($relation->getOwnerKeyName())->toBe('memb___id');
});

it('has wallet relationship', function () {
    $member = new Member;
    $relation = $member->wallet();

    expect($relation)->toBeInstanceOf(HasOne::class)
        ->and($relation->getRelated())->toBeInstanceOf(Wallet::class)
        ->and($relation->getForeignKeyName())->toBe('AccountID');
});
