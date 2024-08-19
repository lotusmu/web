<?php

use App\Models\Credit;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');
});

describe('User and Member Integration', function () {
    it('creates a member when creating a user', function () {
        $name = fakeUsername();
        $email = fakeEmail();

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
        ]);

        expect($user)->toBeInstanceOf(User::class);

        $this->assertDatabaseHas('users', compact('name', 'email'));
        $this->assertDatabaseHas('MEMB_INFO', [
            'memb___id' => $name,
            'mail_addr' => $email,
        ], 'gamedb_main');

        expect($user->member)
            ->not->toBeNull()
            ->and($user->member->name)->toBe($name)
            ->and($user->member->email)->toBe($email)
            ->and($user->member->password)->toBe('password');
    });

    it('updates member email when updating user email', function () {
        $user = User::create([
            'name' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $newEmail = fakeEmail();
        $user->email = $newEmail;
        $user->save();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $newEmail,
        ]);

        $this->assertDatabaseHas('MEMB_INFO', [
            'memb___id' => $user->name,
            'mail_addr' => $newEmail,
        ], 'gamedb_main');

        expect($user->fresh()->member)
            ->not->toBeNull()
            ->and($user->member->email)->toBe($newEmail);
    });

    it('updates member password when updating user password', function () {
        $user = User::create([
            'name' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $newPassword = fakePassword();
        $user->password = $newPassword;
        $user->save();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
        expect(Hash::check($newPassword, $user->fresh()->password))->toBeTrue();

        $this->assertDatabaseHas('MEMB_INFO', [
            'memb___id' => $user->name,
        ], 'gamedb_main');

        expect($user->fresh()->member)
            ->not->toBeNull()
            ->and($user->member->password)->toBe($newPassword);
    });

    it('deletes member when deleting user', function () {
        $user = User::create([
            'name' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $name = $user->name;
        $memberId = $user->member->name;

        $user->delete();

        $this->assertDatabaseMissing('users', ['name' => $name]);

        $this->assertDatabaseMissing('MEMB_INFO', ['memb___id' => $memberId], 'gamedb_main');

        expect(User::find($name))->toBeNull()
            ->and(Member::find($memberId))->toBeNull();
    });
});

describe('Member Model', function () {
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
            'zen',
        ];

        expect((new Member)->getFillable())->toContain(...$expectedFillable);
    });

    it('has working name and password attribute accessors', function () {
        // Test setting attributes via constructor
        $initialUsername = fakeUsername();
        $initialPassword = fakePassword();
        $member = new Member([
            'memb___id' => $initialUsername,
            'memb__pwd' => $initialPassword,
        ]);

        expect($member->name)->toBe($initialUsername)
            ->and($member->password)->toBe($initialPassword);

        // Test setting attributes via accessors
        $newUsername = fakeUsername();
        $newPassword = fakePassword();

        $member->name = $newUsername;
        $member->password = $newPassword;

        expect($member->name)->toBe($newUsername)
            ->and($member->memb___id)->toBe($newUsername)
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

    it('has credit relationship', function () {
        $member = new Member;
        $relation = $member->credit();

        expect($relation)->toBeInstanceOf(HasOne::class)
            ->and($relation->getRelated())->toBeInstanceOf(Credit::class)
            ->and($relation->getForeignKeyName())->toBe('AccountID');
    });
});
