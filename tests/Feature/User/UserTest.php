<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

it('creates a user successfully', function () {
    $user = User::factory()->create();
    $this->assertModelExists($user);
});

it('ensures user factory generates valid data', function () {
    $user = User::factory()->create();
    $this->assertNotEmpty($user->username);
    $this->assertNotEmpty($user->email);
    $this->assertNotEmpty($user->password);
});

it('retrieves a user by ID', function () {
    $user = User::factory()->create();
    $retrievedUser = User::find($user->id);
    $this->assertEquals($user->username, $retrievedUser->username);
});

it('updates the user email successfully', function () {
    $user = User::factory()->create();
    $user->email = 'newemail@example.com';
    $user->save();
    $this->assertEquals('newemail@example.com', $user->fresh()->email);
});

it('deletes a user successfully', function () {
    $user = User::factory()->create();
    $user->delete();
    $this->assertModelMissing($user);
});

it('hashes the user password before saving', function () {
    $user = User::factory()->create(['password' => 'password']);
    $this->assertTrue(Hash::check('password', $user->password));
});

it('checks user has correct fillable attributes', function () {
    $user = new User;
    $fillable = ['username', 'email', 'password'];
    $this->assertEquals($fillable, $user->getFillable());
});

it('checks user has correct hidden attributes', function () {
    $user = new User;
    $hidden = ['password', 'remember_token'];
    $this->assertEquals($hidden, $user->getHidden());
});

it('throws an exception when creating a user with a duplicate email', function () {
    User::factory()->create(['email' => 'test@example.com']);
    $this->expectException(QueryException::class);
    User::factory()->create(['email' => 'test@example.com']);
});

it('throws an exception when creating a user with a duplicate username', function () {
    $username = substr(fake()->userName, 0, 10);

    User::factory()->create(['username' => $username]);
    $this->expectException(QueryException::class);
    User::factory()->create(['username' => $username]);
});

it('throws an exception when trying to update the username', function () {
    $user = User::factory()->create();
    $originalUsername = $user->username;

    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Username cannot be updated after creation.');

    $user->username = 'new_username';
    $user->save();

    // Verify the username didn't change in the database
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => $originalUsername,
    ]);
});

it('throws an exception when creating a user without a username', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Username is required when creating a new user.');

    User::factory()->create(['username' => null]);
});

it('verifies the user has a gameUser relationship', function () {
    $user = User::factory()->create();
    $this->assertInstanceOf(HasOne::class, $user->gameUser());
});

it('ensures plain password is not stored in the database', function () {
    $user = User::factory()->create(['password' => 'password']);
    $this->assertNull($user->plainPassword);
});

it('allows the user password to be updated and rehashed', function () {
    $user = User::factory()->create(['password' => 'oldpwd']);
    $user->password = 'newpwd';
    $user->save();
    $this->assertTrue(Hash::check('newpwd', $user->fresh()->password));
});
