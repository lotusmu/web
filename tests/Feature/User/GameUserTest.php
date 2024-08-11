<?php

use App\Models\Game\User as GameUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

describe('User and GameUser Integration', function () {
    it('creates a game user when creating a user', function () {
        $username = fakeUsername();
        $email = fakeEmail();

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => 'password',
        ]);

        expect($user)->toBeInstanceOf(User::class);

        $this->assertDatabaseHas('users', compact('username', 'email'));
        $this->assertDatabaseHas('MEMB_INFO', [
            'memb___id' => $username,
            'mail_addr' => $email,
        ], 'game_server_1');

        expect($user->gameUser)
            ->not->toBeNull()
            ->and($user->gameUser->username)->toBe($username)
            ->and($user->gameUser->password)->toBe('password');
    });

    it('updates game user email when updating user email', function () {
        $user = User::create([
            'username' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $newEmail = fakeEmail();
        $user->email = $newEmail;
        $user->save();

        $this->assertDatabaseHas('users', [
            'username' => $user->username,
            'email' => $newEmail,
        ]);

        $this->assertDatabaseHas('MEMB_INFO', [
            'memb___id' => $user->username,
            'mail_addr' => $newEmail,
        ], 'game_server_1');
    });

    it('updates game user password when updating user password', function () {
        $user = User::create([
            'username' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $newPassword = fakePassword();
        $user->password = $newPassword;
        $user->save();

        expect($user->fresh()->gameUser->password)->toBe($newPassword);
    });

    it('deletes game user when deleting user', function () {
        $user = User::create([
            'username' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => 'password',
        ]);

        $gameUserId = $user->gameUser->username;

        $user->delete();

        $this->assertDatabaseMissing('users', ['username' => $user->username]);
        $this->assertDatabaseMissing('MEMB_INFO', ['memb___id' => $gameUserId], 'game_server_1');
    });
});

describe('GameUser Model', function () {
    it('uses the correct database connection and table name', function () {
        $gameUser = new GameUser;
        expect($gameUser->getConnectionName())->toBe('game_server_1')
            ->and($gameUser->getTable())->toBe('MEMB_INFO');
    });

    it('has the correct primary key configuration', function () {
        $gameUser = new GameUser;
        expect($gameUser->getKeyName())->toBe('memb___id')
            ->and($gameUser->getKeyType())->toBe('string')
            ->and($gameUser->incrementing)->toBeFalse();
    });

    it('does not use timestamps', function () {
        expect((new GameUser)->timestamps)->toBeFalse();
    });

    it('has the correct fillable attributes', function () {
        $expectedFillable = [
            'memb___id', 'memb__pwd', 'memb_name', 'sno__numb', 'mail_addr',
            'appl_days', 'mail_chek', 'bloc_code', 'ctl1_code', 'AccountLevel', 'AccountExpireDate'
        ];
        expect((new GameUser)->getFillable())->toContain(...$expectedFillable);
    });

    it('has username and password attributes', function () {
        $username = fakeUsername();
        $password = fakePassword();

        $gameUser = new GameUser([
            'memb___id' => $username,
            'memb__pwd' => $password
        ]);

        expect($gameUser->username)->toBe($username)
            ->and($gameUser->password)->toBe($password);
    });

    it('can set username and password attributes', function () {
        $gameUser = new GameUser;

        $username = fakeUsername();
        $gameUser->username = $username;
        expect($gameUser->memb___id)->toBe($username);

        $password = fakePassword();
        $gameUser->password = $password;
        expect($gameUser->memb__pwd)->toBe($password);
    });

    it('belongs to a user', function () {
        $gameUser = new GameUser;
        $relation = $gameUser->user();

        expect($relation)->toBeInstanceOf(BelongsTo::class)
            ->and($relation->getRelated())->toBeInstanceOf(User::class)
            ->and($relation->getForeignKeyName())->toBe('memb___id')
            ->and($relation->getOwnerKeyName())->toBe('username');
    });
});
