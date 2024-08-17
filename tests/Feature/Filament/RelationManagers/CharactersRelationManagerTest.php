<?php

use App\Filament\Resources\MemberResource\RelationManagers\CharactersRelationManager;
use App\Filament\Tables\Columns\CharacterClassColumn;
use App\Models\Character;
use App\Models\Member;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();
    $this->member = $user->member;
    $this->character1 = Character::factory()->forUser($user)->create();
    $this->character2 = Character::factory()->forUser($user)->create();
});

it('can render the relation manager', function () {
    livewire(CharactersRelationManager::class, [
        'ownerRecord' => Member::find('void'),
    ])->assertSuccessful();
});

it('displays the correct columns', function () {
    $component = livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
    ])->assertSuccessful();

    expect($component->instance()->table->getColumns())
        ->toHaveCount(4)
        ->sequence(
            fn ($column) => $column->getName()->toBe('Name')
                ->and($column)->toBeInstanceOf(TextColumn::class),
            fn ($column) => $column->getName()->toBe('Class')
                ->and($column)->toBeInstanceOf(CharacterClassColumn::class),
            fn ($column) => $column->getName()->toBe('cLevel')
                ->and($column)->toBeInstanceOf(TextColumn::class),
            fn ($column) => $column->getName()->toBe('ResetCount')
                ->and($column)->toBeInstanceOf(TextColumn::class)
        );
});

it('displays the correct character data', function () {
    livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
    ])
        ->assertCanSeeTableRecords($this->member->characters)
        ->assertTableColumnStateSet('Name', $this->charName1, 1)
        ->assertTableColumnStateSet('Class', 'Warrior', 1)
        ->assertTableColumnStateSet('cLevel', '10', 1)
        ->assertTableColumnStateSet('ResetCount', '0', 1)
        ->assertTableColumnStateSet('Name', $this->charName2, 2)
        ->assertTableColumnStateSet('Class', 'Mage', 2)
        ->assertTableColumnStateSet('cLevel', '20', 2)
        ->assertTableColumnStateSet('ResetCount', '1', 2);
});

it('has a view action', function () {
    $component = livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
    ])->assertSuccessful();

    $actions = $component->instance()->table->getActions();

    expect($actions)->toHaveCount(1)
        ->and($actions[0])->toBeInstanceOf(Action::class)
        ->and($actions[0]->getName())->toBe('view')
        ->and($actions[0]->getLabel())->toBe('View')
        ->and($actions[0]->getIcon())->toBe('heroicon-s-eye');
});

it('generates correct view URL for characters', function () {
    $component = livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
    ])->assertSuccessful();

    $viewAction = $component->instance()->table->getAction('view');
    $character = $this->member->characters->first();

    $url = $viewAction->getUrl($character);
    $expectedUrl = route('filament.admin.resources.characters.view', $character);

    expect($url)->toBe($expectedUrl);
});

it('allows sorting by level and reset count', function () {
    livewire(CharactersRelationManager::class, [
        'ownerRecord' => $this->member,
    ])
        ->assertCanSortTableColumn('cLevel')
        ->assertCanSortTableColumn('ResetCount');
});
