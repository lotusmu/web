<?php

use App\Filament\Resources\MemberResource\Pages\EditMember;
use App\Filament\Resources\MemberResource\RelationManagers\CharactersRelationManager;
use App\Models\Game\Character;
use App\Models\User\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();
    $this->member = $user->member;

    $this->character1 = Character::factory()->forUser($user)->create();
    $this->character2 = Character::factory()->forUser($user)->create();
});

it('can render relation manager', function () {
    livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
        'pageClass' => EditMember::class,
    ])
        ->assertSuccessful();
});

it('can list characters', function () {
    livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
        'pageClass' => EditMember::class,
    ])
        ->assertCanSeeTableRecords($this->member->characters);
});

it('generates correct view URL for characters', function () {
    $character = $this->member->characters->first();
    $expectedUrl = route('filament.admin.resources.characters.view', $character);

    livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
        'pageClass' => EditMember::class,
    ])
        ->assertSuccessful()
        ->assertTableActionExists('view')
        ->assertTableActionHasUrl('view', $expectedUrl, $character);
});
