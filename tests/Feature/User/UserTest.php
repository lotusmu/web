<?php

use App\Interfaces\HasGameUser;
use App\Models\Game\User as GameUser;
use App\Models\User;
use App\Services\GameUserService;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

describe('User Creation', function () {
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

    it('throws an exception when creating a user without a username', function () {
        User::factory()->create(['username' => null]);
    })->throws(InvalidArgumentException::class, 'Username is required when creating a new user.');

    it('creates a game user when a new user is created', function () {
        $user = User::factory()->create();
        expect($user->gameUser)->not->toBeNull();
    });

    it('uses GameUserService to create game user', function () {
        $mock = $this->mock(GameUserService::class);
        $mock->shouldReceive('createGameUser')->once();
        User::factory()->create();
    });
});

describe('User Retrieval and Update', function () {
    it('retrieves a user by ID', function () {
        $user = User::factory()->create();
        $retrievedUser = User::find($user->id);
        expect($retrievedUser->username)->toBe($user->username);
    });

    it('updates the user email successfully', function () {
        $user = User::factory()->create();
        $newEmail = fake()->safeEmail();
        $user->email = $newEmail;
        $user->save();
        expect($user->fresh()->email)->toBe($newEmail);
    });

    it('throws an exception when trying to update the username', function () {
        $user = User::factory()->create();
        $originalUsername = $user->username;

        expect(function () use ($user) {
            $user->username = fake()->userName();
            $user->save();
        })->toThrow(InvalidArgumentException::class, 'Username cannot be updated after creation.')
            ->and($user->fresh()->username)->toBe($originalUsername);
    });

    it('uses GameUserService to update game user', function () {
        $user = User::factory()->create();
        $mock = $this->mock(GameUserService::class);
        $mock->shouldReceive('updateGameUser')->once();
        $user->email = fake()->safeEmail();
        $user->save();
    });
});

describe('User Deletion', function () {
    it('deletes a user successfully', function () {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        expect(User::find($userId))->toBeNull();
    });

    it('deletes game user when user is deleted', function () {
        $user = User::factory()->create();
        $username = $user->username;

        expect(GameUser::where('memb___id', $username)->exists())->toBeTrue();

        $user->delete();

        expect(GameUser::where('memb___id', $username)->exists())->toBeFalse();
    });
});

describe('Password Handling', function () {
    it('hashes the user password before saving and stores raw password in game user', function () {
        $password = 'password';
        $user = User::factory()->create(['password' => $password]);

        expect(Hash::check($password, $user->password))->toBeTrue()
            ->and($user->getRawPassword())->toBeNull();

        $gameUser = GameUser::where('memb___id', $user->username)->first();
        expect($gameUser->memb__pwd)->toBe($password);
    });

    it('allows the user password to be updated and rehashed', function () {
        $user = User::factory()->create();
        $newPassword = fakePassword();
        $user->password = $newPassword;
        $user->save();
        expect(Hash::check($newPassword, $user->fresh()->password))->toBeTrue();
    });
});

describe('Model Attributes', function () {
    it('checks user has correct fillable attributes', function () {
        $user = new User;
        expect($user->getFillable())->toBe(['username', 'email', 'password']);
    });

    it('checks user has correct hidden attributes', function () {
        $user = new User;
        expect($user->getHidden())->toBe(['password', 'remember_token']);
    });
});

describe('Uniqueness Constraints', function () {
    it('throws an exception when creating a user with a duplicate email or username', function ($attribute) {
        $value = $attribute === 'email' ? fakeEmail() : fakeUsername();
        User::factory()->create([$attribute => $value]);
        User::factory()->create([$attribute => $value]);
    })->with(['email', 'username'])->throws(QueryException::class);
});

describe('Relationships and Interfaces', function () {
    it('verifies the user has a gameUser relationship', function () {
        $user = User::factory()->create();
        expect($user->gameUser())->toBeInstanceOf(HasOne::class);
    });

    it('implements HasGameUser interface', function () {
        $user = new User;
        expect($user)->toBeInstanceOf(HasGameUser::class);
    });
});
