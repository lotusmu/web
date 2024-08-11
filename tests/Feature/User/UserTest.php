<?php

use App\Interfaces\HasMember;
use App\Models\Member;
use App\Models\User;
use App\Services\MemberService;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

describe('User Creation', function () {
    it('ensures user factory generates valid data', function () {
        $user = User::factory()->create();
        expect($user->username)->not->toBeEmpty()
            ->and($user->email)->not->toBeEmpty()
            ->and($user->password)->not->toBeEmpty();
    });

    it('creates a user successfully', function () {
        $user = User::factory()->create();
        expect($user)->toBeTruthy();
    });

    it('throws an exception when creating a user without a username', function () {
        User::factory()->create(['username' => null]);
    })->throws(InvalidArgumentException::class, 'Username is required when creating a new user.');

    it('creates a member when a new user is created', function () {
        $memberService = $this->mock(MemberService::class);
        $memberService->shouldReceive('createMember')->once()->andReturnUsing(function ($user) {
            return Member::create([
                'memb___id' => $user->username,
                'memb__pwd' => $user->getRawPassword(),
                'memb_name' => $user->username,
                'mail_addr' => $user->email,
                'sno__numb' => 1111111111111,
                'appl_days' => 0,
                'mail_chek' => 0,
                'bloc_code' => 0,
                'ctl1_code' => 0,
                'AccountLevel' => 0,
                'AccountExpireDate' => now(),
            ]);
        });

        $user = User::factory()->create();

        expect($user->member)->not->toBeNull()
            ->and($user->member->username)->toBe($user->username)
            ->and($user->member->email)->toBe($user->email);

        $memberService->shouldHaveReceived('createMember')->once()->with(Mockery::type(User::class));
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
        $newEmail = fakeEmail();
        $user->email = $newEmail;
        $user->save();
        expect($user->fresh()->email)->toBe($newEmail);
    });

    it('throws an exception when trying to update the username', function () {
        $user = User::factory()->create();
        $originalUsername = $user->username;

        expect(function () use ($user) {
            $user->username = fakeUsername();
            $user->save();
        })->toThrow(InvalidArgumentException::class, 'Username cannot be updated after creation.')
            ->and($user->fresh()->username)->toBe($originalUsername);
    });

    it('updates a member when a user is updated', function () {
        $user = User::factory()->create();
        $oldEmail = $user->email;
        $newEmail = fakeEmail();

        $memberService = $this->mock(MemberService::class);
        $memberService->shouldReceive('updateMember')
            ->once()
            ->with(Mockery::on(function ($arg) use ($user, $newEmail) {
                return $arg instanceof User
                    && $arg->id === $user->id
                    && $arg->email === $newEmail;
            }))
            ->andReturnUsing(function ($user) {
                $user->member()->update([
                    'mail_addr' => $user->email,
                ]);
            });

        $user->email = $newEmail;
        $user->save();

        $user->refresh();
        expect($user->email)->toBe($newEmail)
            ->and($user->member->email)->toBe($newEmail)
            ->and($user->member->email)->not->toBe($oldEmail);

        $memberService->shouldHaveReceived('updateMember')->once()->with(Mockery::type(User::class));
    });
});

describe('User Deletion', function () {
    it('deletes a user successfully', function () {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        expect(User::find($userId))->toBeNull();
    });

    it('deletes member when user is deleted', function () {
        $user = User::factory()->create();
        $username = $user->username;

        expect(Member::where('memb___id', $username)->exists())->toBeTrue();

        $user->delete();

        expect(Member::where('memb___id', $username)->exists())->toBeFalse();
    });
});

describe('Password Handling', function () {
    it('hashes the user password and stores raw password in member', function () {
        $password = 'password';

        $user = User::factory()->create(['password' => $password]);
        $member = Member::where('memb___id', $user->username)->first();

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
        $member = Member::where('memb___id', $user->username)->first();

        expect($user->password)->not->toBe($newPassword)
            ->and(Hash::check($newPassword, $user->password))->toBeTrue()
            ->and($member->password)->toBe($newPassword)
            ->and($member->password)->not->toBe($initialPassword);
    });
});

describe('Model Attributes', function () {
    it('checks user has correct fillable attributes', function () {
        $user = new User;
        expect($user->getFillable())->toBe([
            'username',
            'email',
            'password',
        ]);
    });

    it('checks user has correct hidden attributes', function () {
        $user = new User;
        expect($user->getHidden())->toBe([
            'password',
            'remember_token',
        ]);
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
    it('verifies the user has a member relationship', function () {
        $user = User::factory()->create();
        expect($user->member())->toBeInstanceOf(HasOne::class);
    });

    it('implements HasMember interface', function () {
        $user = new User;
        expect($user)->toBeInstanceOf(HasMember::class);
    });
});
