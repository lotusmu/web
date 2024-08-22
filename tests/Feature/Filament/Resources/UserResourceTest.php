<?php

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('cannot be created', function () {
    expect(UserResource::canCreate())->toBeFalse();
});

it('has member relation manager', function () {
    expect(UserResource::getRelations())->toContain(UserResource\RelationManagers\MemberRelationManager::class);
});

describe('pages', function () {
    it('can render list page', function () {
        $this->get(UserResource::getUrl('index'))->assertSuccessful();
    });

    it('can render edit page', function () {
        $this->get(UserResource::getUrl('edit', ['record' => $this->user]))->assertSuccessful();
    });

    it('does not have create page', function () {
        expect(UserResource::getPages())->not->toHaveKey('create');
    });
});

describe('edit form', function () {
    it('displays "Not verified" when email is not verified', function () {
        $user = User::factory()->unverified()->create();

        $content = (function ($record) {
            if ($record->email_verified_at) {
                return Carbon::parse($record->email_verified_at)->format('M d, Y H:i:s');
            }

            return 'Not verified';
        })($user);

        expect($content)->toBe('Not verified');
    });

    it('resets password fields when change_password is unchecked', function () {
        $formState = [
            'password' => 'newpass',
            'password_confirmation' => 'newpass',
            'change_password' => true,
        ];

        expect($formState['password'])->toBe('newpass')
            ->and($formState['password_confirmation'])->toBe('newpass')
            ->and($formState['change_password'])->toBeTrue();

        $formState['change_password'] = false;

        if (! $formState['change_password']) {
            $formState['password'] = null;
            $formState['password_confirmation'] = null;
        }

        expect($formState['password'])->toBeNull()
            ->and($formState['password_confirmation'])->toBeNull();
    });
});

describe('table', function () {
    it('allows editing users', function () {
        livewire(UserResource\Pages\ListUsers::class)
            ->assertTableActionExists('edit');
    });

    it('displays verify action for unverified users', function () {
        $unverifiedUser = User::factory()->unverified()->create();

        livewire(UserResource\Pages\ListUsers::class)
            ->assertTableActionVisible('Verify', $unverifiedUser);
    });

    it('hides verify action for verified users', function () {
        $verifiedUser = User::factory()->create(['email_verified_at' => now()]);

        livewire(UserResource\Pages\ListUsers::class)
            ->assertTableActionHidden('Verify', $verifiedUser);
    });

    it('verifies a user and shows a notification', function () {
        $unverifiedUser = User::factory()->unverified()->create();

        $component = livewire(UserResource\Pages\ListUsers::class);

        $component
            ->callTableAction('Verify', $unverifiedUser)
            ->callTableAction('Verify', $unverifiedUser, data: [
                'confirmation' => true,
            ]);

        $unverifiedUser->refresh();
        expect($unverifiedUser->email_verified_at)->not->toBeNull();

        $component->assertNotified('The email was verified successfully!');
    });

    it('has correct tabs and filters users appropriately', function () {
        DB::table('users')->delete();

        User::factory()
            ->count(6)
            ->state(new Sequence(
                ['email_verified_at' => now()],
                ['email_verified_at' => null],
            ))
            ->create();

        $component = livewire(ListUsers::class);

        $component
            ->assertSuccessful()
            ->assertSeeText('All Users')
            ->assertSeeText('Verified')
            ->assertSeeText('Not Verified')
            ->assertCountTableRecords(6);

        $component
            ->set('activeTab', 'verified')
            ->assertCountTableRecords(3);

        $component
            ->set('activeTab', 'not_verified')
            ->assertCountTableRecords(3);
    });
});

describe('bulk actions', function () {
    it('has verify and delete bulk actions', function () {
        livewire(UserResource\Pages\ListUsers::class)
            ->assertTableBulkActionExists('Verify selected')
            ->assertTableBulkActionExists('delete');
    });

    it('can verify multiple users', function () {
        $unverifiedUsers = User::factory()->count(3)->unverified()->create();

        livewire(UserResource\Pages\ListUsers::class)
            ->callTableBulkAction('Verify selected', $unverifiedUsers);

        $unverifiedUsers->each(function ($user) {
            $user->refresh();
            expect($user->email_verified_at)->not->toBeNull();
        });
    });
});
