<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('creates a game user when creating a user', function () {
    $username = substr(fake()->userName, 0, 10);
    $email = fake()->unique()->safeEmail();

    $user = User::create([
        'username' => $username,
        'email' => $email,
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class);

    $this->assertDatabaseHas('users', [
        'username' => $username,
        'email' => $email,
    ]);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $username,
        'mail_addr' => $email,
    ], 'game_server_1');

    expect($user->gameUser)->not->toBeNull()
        ->and($user->gameUser->memb___id)->toBe($username)
        ->and($user->gameUser->memb__pwd)->toBe('password');
});

it('updates game user email when updating user email', function () {
    $username = substr(fake()->userName, 0, 10);

    $user = User::create([
        'username' => $username,
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password',
    ]);

    $newEmail = fake()->unique()->safeEmail();

    $user->email = $newEmail;
    $user->save();

    $this->assertDatabaseHas('users', [
        'username' => $username,
        'email' => $newEmail,
    ]);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $username,
        'mail_addr' => $newEmail,
    ], 'game_server_1');
});

it('updates game user password when updating user password', function () {
    $user = User::create([
        'username' => substr(fake()->userName, 0, 10),
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password',
    ]);

    $newPassword = fake()->password(4, 10);

    $user->password = $newPassword;
    $user->save();

    expect($user->fresh()->gameUser->memb__pwd)->toBe($newPassword);
});

it('deletes game user when deleting user', function () {
    $username = substr(fake()->userName, 0, 10);
    $email = fake()->unique()->safeEmail();

    $user = User::create([
        'username' => $username,
        'email' => $email,
        'password' => 'password',
    ]);

    $gameUserId = $user->gameUser->memb___id;

    $user->delete();

    $this->assertDatabaseMissing('users', ['username' => $username]);
    $this->assertDatabaseMissing('MEMB_INFO', ['memb___id' => $gameUserId], 'game_server_1');
});

it('uses the correct database connection', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->getConnectionName())->toBe('game_server_1');
});

it('uses the correct table name', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->getTable())->toBe('MEMB_INFO');
});

it('has the correct primary key', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->getKeyName())->toBe('memb___id')
        ->and($gameUser->getKeyType())->toBe('string')
        ->and($gameUser->incrementing)->toBeFalse();
});

it('does not use timestamps', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->timestamps)->toBeFalse();
});

it('has the correct fillable attributes', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->getFillable())->toContain('memb___id', 'memb__pwd', 'memb_name', 'sno__numb', 'mail_addr',
        'appl_days', 'mail_chek', 'bloc_code', 'ctl1_code', 'AccountLevel', 'AccountExpireDate');
});

it('has a username attribute', function () {
    $username = substr(fake()->userName, 0, 10);

    $gameUser = new \App\Models\Game\User(['memb___id' => $username]);
    expect($gameUser->username)->toBe($username);
});

it('has a password accessor', function () {
    $gameUser = new \App\Models\Game\User(['memb__pwd' => 'password123']);
    expect($gameUser->password)->toBe('password123');
});

it('can set username attribute', function () {
    $username = substr(fake()->userName, 0, 10);

    $gameUser = new \App\Models\Game\User;
    $gameUser->username = $username;
    expect($gameUser->memb___id)->toBe($username);
});

it('has a password mutator', function () {
    $gameUser = new \App\Models\Game\User;
    $gameUser->password = 'newpass';
    expect($gameUser->memb__pwd)->toBe('newpass');
});

it('belongs to a user', function () {
    $gameUser = new \App\Models\Game\User;
    expect($gameUser->user())->toBeInstanceOf(BelongsTo::class)
        ->and($gameUser->user()->getRelated())->toBeInstanceOf(User::class)
        ->and($gameUser->user()->getForeignKeyName())->toBe('memb___id')
        ->and($gameUser->user()->getOwnerKeyName())->toBe('username');
});
