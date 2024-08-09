<?php

use App\Models\User;

it('creates a game user when creating a user', function () {
    $username = substr(fake()->userName, 0, 10);
    $email = fake()->unique()->safeEmail();


    $user = User::create([
        'username' => $username,
        'email' => $email,
        'password' => 'password',
    ]);

    $this->assertDatabaseHas('users', [
        'username' => $username,
        'email' => $email,
    ]);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $username,
        'mail_addr' => $email,
    ], 'game_server_1');

    $gameUser = $user->gameUser;
    $this->assertNotNull($gameUser);
    $this->assertEquals($username, $gameUser->memb___id);
    $this->assertEquals('password', $gameUser->memb__pwd);
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

    $gameUser = $user->fresh()->gameUser;
    $this->assertEquals($newPassword, $gameUser->memb__pwd);
});

it('deletes game user when deleting user also', function () {
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
