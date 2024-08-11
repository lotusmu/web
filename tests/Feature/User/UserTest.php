<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

it('creates a user successfully', function () {
    $user = User::factory()->create();
    expect($user)->toBeTruthy();
});

it('ensures user factory generates valid data', function () {
    $user = User::factory()->create();
    expect($user->username)->not->toBeEmpty()
        ->and($user->email)->not->toBeEmpty()
        ->and($user->password)->not->toBeEmpty();
});

it('retrieves a user by ID', function () {
    $user = User::factory()->create();
    $retrievedUser = User::find($user->id);
    expect($retrievedUser->username)->toBe($user->username);
});

it('updates the user email successfully', function () {
    $user = User::factory()->create();
    $user->email = 'newemail@example.com';
    $user->save();
    expect($user->fresh()->email)->toBe('newemail@example.com');
});

it('deletes a user successfully', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(User::find($userId))->toBeNull();
});

it('hashes the user password before saving', function () {
    $user = User::factory()->create(['password' => 'password']);
    expect(Hash::check('password', $user->password))->toBeTrue();
});

it('checks user has correct fillable attributes', function () {
    $user = new User;
    expect($user->getFillable())->toBe(['username', 'email', 'password']);
});

it('checks user has correct hidden attributes', function () {
    $user = new User;
    expect($user->getHidden())->toBe(['password', 'remember_token']);
});

it('throws an exception when creating a user with a duplicate email', function () {
    User::factory()->create(['email' => 'test@example.com']);
    User::factory()->create(['email' => 'test@example.com']);
})->throws(QueryException::class);

it('throws an exception when creating a user with a duplicate username', function () {
    $username = substr(fake()->userName, 0, 10);
    User::factory()->create(['username' => $username]);
    User::factory()->create(['username' => $username]);
})->throws(QueryException::class);

it('throws an exception when trying to update the username', function () {
    $user = User::factory()->create();
    $originalUsername = $user->username;

    expect(function () use ($user) {
        $user->username = 'new_username';
        $user->save();
    })->toThrow(InvalidArgumentException::class, 'Username cannot be updated after creation.')
        ->and($user->fresh()->username)->toBe($originalUsername);

});

it('throws an exception when creating a user without a username', function () {
    User::factory()->create(['username' => null]);
})->throws(
    InvalidArgumentException::class,
    'Username is required when creating a new user.'
);

it('verifies the user has a gameUser relationship', function () {
    $user = User::factory()->create();
    expect($user->gameUser())->toBeInstanceOf(HasOne::class);
});

it('ensures plain password is not stored in the database', function () {
    $user = User::factory()->create(['password' => 'password']);
    expect($user->plainPassword)->toBeNull();
});

it('allows the user password to be updated and rehashed', function () {
    $user = User::factory()->create(['password' => 'oldpwd']);
    $user->password = 'newpwd';
    $user->save();
    expect(Hash::check('newpwd', $user->fresh()->password))->toBeTrue();
});
