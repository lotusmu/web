<?php

use App\Interfaces\HasMember;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');

    $this->user = User::factory()->create();
});

describe('User Creation', function () {
    it('ensures user factory generates valid data', function () {
        expect($this->user->name)->not->toBeEmpty()
            ->and($this->user->email)->not->toBeEmpty()
            ->and($this->user->password)->not->toBeEmpty();
    });

    it('creates a user successfully', function () {
        expect($this->user)->toBeTruthy();
    });
});

describe('User Retrieval and Update', function () {
    it('retrieves a user by ID', function () {
        $retrievedUser = User::find($this->user->id);

        expect($retrievedUser->name)->toBe($this->user->name);
    });

    it('updates the user email successfully', function () {
        $newEmail = fakeEmail();

        $this->user->email = $newEmail;
        $this->user->save();

        expect($this->user->fresh()->email)->toBe($newEmail);
    });

    it('updates the user password successfully', function () {
        $oldPassword = fakePassword();
        $newPassword = fakePassword();

        $user = User::factory()->create([
            'password' => $oldPassword,
        ]);

        $user->password = $newPassword;
        $user->save();

        $freshUser = $user->fresh();

        expect(Hash::check($newPassword, $freshUser->password))->toBeTrue()
            ->and(Hash::check($oldPassword, $freshUser->password))->toBeFalse()
            ->and($freshUser->getRawPassword())->toBeNull();
    });

    it('throws an exception when trying to update the name', function () {
        $user = $this->user;
        $originalUsername = $user->name;

        expect(function () use ($user) {
            $user->name = fakeUsername();
            $user->save();
        })->toThrow(InvalidArgumentException::class, 'Username cannot be updated after creation.')
            ->and($user->fresh()->name)->toBe($originalUsername);
    });
});

describe('Password Handling', function () {
    it('hashes the user password and stores raw password in member', function () {
        $password = 'password';

        $user = User::factory()->create(['password' => $password]);
        $member = Member::where('memb___id', $user->name)->first();

        expect($user->password)->not->toBe($password)
            ->and(Hash::check($password, $user->password))->toBeTrue()
            ->and($user->getRawPassword())->toBeNull()
            ->and($member->password)->toBe($password);
    });

    it('updates and rehashes user password while updating member plain text password', function () {
        $initialPassword = 'password';
        $user = User::factory()->create(['password' => $initialPassword]);
        $newPassword = 'newpwd';

        $user->password = $newPassword;
        $user->save();

        $user->refresh();
        $member = Member::where('memb___id', $user->name)->first();

        expect($user->password)->not->toBe($newPassword)
            ->and(Hash::check($newPassword, $user->password))->toBeTrue()
            ->and($member->password)->toBe($newPassword)
            ->and($member->password)->not->toBe($initialPassword);
    });
});

describe('Model Attributes', function () {
    it('checks user has correct fillable attributes', function () {
        expect($this->user->getFillable())->toBe([
            'name',
            'email',
            'password',
        ]);
    });

    it('checks user has correct hidden attributes', function () {
        expect($this->user->getHidden())->toBe([
            'password',
            'remember_token',
        ]);
    });
});

describe('Uniqueness Constraints', function () {
    it('throws an exception when creating a user with a duplicate email or name', function ($attribute) {
        $value = $attribute === 'email' ? fakeEmail() : fakeUsername();
        User::factory()->create([$attribute => $value]);
        User::factory()->create([$attribute => $value]);
    })->with(['email', 'name'])->throws(QueryException::class);
});

describe('Relationships and Interfaces', function () {
    it('verifies the user has a member relationship', function () {
        expect($this->user->member())->toBeInstanceOf(HasOne::class);
    });

    it('implements HasMember interface', function () {
        expect($this->user)->toBeInstanceOf(HasMember::class);
    });
});
