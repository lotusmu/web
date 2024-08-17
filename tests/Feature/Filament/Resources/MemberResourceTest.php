<?php

use App\Enums\AccountLevel;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\MemberResource;
use App\Filament\Resources\MemberResource\Pages\EditMember;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Livewire\livewire;

describe('Render', function () {
    it('can render index page', function () {
        $this->get(MemberResource::getUrl('index'))
            ->assertSuccessful();
    });

    it('can render edit page', function () {
        $user = User::factory()->create();

        $this->get(MemberResource::getUrl('edit', [$user->name]))
            ->assertSuccessful();
    });
});

describe('Create & Delete restrictions', function () {
    it('returns false on canCreate', function () {
        $result = CharacterResource::canCreate();

        $this->assertFalse($result);
    });

    it('returns false on canDelete', function () {
        $user = User::factory()->create();

        $result = MemberResource::canDelete($user->member);

        $this->assertFalse($result);
    });
});

describe('Edit', function () {
    it('validates account level and expiration date', function () {
        $user = User::factory()->create();
        $member = $user->member;

        livewire(MemberResource\Pages\EditMember::class, [
            'record' => $member->getKey(),
        ])
            ->fillForm([
                'AccountLevel' => 'invalid_level',
                'AccountExpireDate' => 'not-a-date',
            ])
            ->call('save');

        $member->refresh();
        expect($member->AccountLevel)->not->toBe('invalid_level')
            ->and($member->AccountExpireDate)->not->toBe('not-a-date');
    });

    it('accepts valid account level and expiration date without errors', function () {
        $user = User::factory()->create();
        $member = $user->member;
        $expirationDate = now()->addYear()->startOfMinute();

        livewire(EditMember::class, ['record' => $member->getRouteKey()])
            ->fillForm([
                'AccountLevel' => AccountLevel::Bronze,
                'AccountExpireDate' => $expirationDate,
            ])
            ->call('save')
            ->assertHasNoErrors(['AccountLevel', 'AccountExpireDate']);
    });

    it('saves valid account level and expiration date to the database', function () {
        $user = User::factory()->create();
        $member = $user->member;
        $expirationDate = now()->addYear()->startOfMinute();

        livewire(EditMember::class, ['record' => $member->getRouteKey()])
            ->fillForm([
                'AccountLevel' => AccountLevel::Bronze,
                'AccountExpireDate' => $expirationDate,
            ])
            ->call('save');

        $member->refresh();

        expect($member->AccountLevel)->toBe(AccountLevel::Bronze);

        $storedDate = $member->AccountExpireDate;
        if (is_string($storedDate)) {
            $storedDate = Carbon::parse($storedDate);
        }

        expect($storedDate->startOfMinute()->toDateTimeString())
            ->toBe($expirationDate->toDateTimeString());
    });
});

describe('Global Search', function () {
    it('returns the correct globally searchable attributes', function () {
        $expectedAttributes = ['memb___id', 'mail_addr'];

        expect(MemberResource::getGloballySearchableAttributes())
            ->toBe($expectedAttributes);
    });

    it('returns the correct global search result title', function () {
        $username = fakeUsername();
        $email = fakeEmail();

        $member = new Member([
            'memb___id' => $username,
            'mail_addr' => $email,
        ]);

        $expectedTitle = $username.' ('.$email.')';

        expect(MemberResource::getGlobalSearchResultTitle($member))
            ->toBe($expectedTitle);
    });
});
