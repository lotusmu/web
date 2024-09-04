<?php

use App\Enums\Game\AccountLevel;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\RelationManagers\MemberRelationManager;
use App\Models\User\User;
use Carbon\Carbon;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->member = $this->user->member;
});

it('can render relation manager', function () {
    livewire(MemberRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertSuccessful();
});

it('can list member', function () {
    livewire(MemberRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertCanSeeTableRecords([$this->user->member]);
});

it('can update member', function () {
    $newExpireDate = now()->addYear()->startOfDay();

    livewire(MemberRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->callTableAction('edit', $this->member, data: [
            'AccountLevel' => AccountLevel::Bronze->value,
            'AccountExpireDate' => $newExpireDate,
        ])
        ->assertHasNoTableActionErrors();

    $this->member->refresh();

    expect($this->member->AccountLevel)->toBe(AccountLevel::Bronze);

    $storedExpireDate = Carbon::parse($this->member->AccountExpireDate)->startOfDay();

    expect($storedExpireDate->equalTo($newExpireDate))->toBeTrue(
        "Expected {$newExpireDate->toDateTimeString()}, but got {$storedExpireDate->toDateTimeString()}"
    );
});
