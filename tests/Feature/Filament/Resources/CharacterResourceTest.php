<?php

use App\Enums\CharacterClass;
use App\Enums\Map;
use App\Enums\PkLevel;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Pages\EditCharacter;
use App\Filament\Resources\CharacterResource\Pages\ViewCharacter;
use App\Models\Game\Character;

use function Pest\Livewire\livewire;

beforeEach(function () {
    refreshTable('Character', 'gamedb_main');

    $this->character = Character::create([
        'AccountID' => fakeUsername(),
        'Name' => fakeUsername(),
        'Class' => CharacterClass::DarkWizard,
        'ResetCount' => '0',
        'cLevel' => '1',
        'Strength' => '25',
        'Dexterity' => '25',
        'Vitality' => '25',
        'Energy' => '25',
        'Leadership' => '0',
        'MapNumber' => Map::Lorencia,
        'MapPosX' => '125',
        'MapPosY' => '125',
        'PkLevel' => PkLevel::Normal,
        'PkCount' => '0',
        'PkTime' => '0',
    ]);
});

describe('Render', function () {
    it('can render index page', function () {
        $this->get(CharacterResource::getUrl('index'))
            ->assertSuccessful();
    });

    it('can render edit page', function () {
        $this->get(CharacterResource::getUrl('edit', [$this->character]))
            ->assertSuccessful();
    });

    it('can retrieve data on edit page', function () {
        livewire(EditCharacter::class, [
            'record' => $this->character->getRouteKey(),
        ])
            ->assertFormSet([
                'Name' => $this->character->Name,
                'Class' => $this->character->Class->value,
                'ResetCount' => $this->character->ResetCount,
                'cLevel' => $this->character->cLevel,
                'Strength' => $this->character->Strength,
                'Dexterity' => $this->character->Dexterity,
                'Vitality' => $this->character->Vitality,
                'Energy' => $this->character->Energy,
                'Leadership' => $this->character->Leadership,
                'MapNumber' => $this->character->MapNumber->value,
                'MapPosX' => $this->character->MapPosX,
                'MapPosY' => $this->character->MapPosY,
                'PkLevel' => $this->character->PkLevel->value,
                'PkCount' => $this->character->PkCount,
                'PkTime' => $this->character->PkTime,
            ]);
    });

    it('can render view page', function () {

        $this->get(CharacterResource::getUrl('view', [$this->character]))
            ->assertSuccessful();
    });

    it('can retrieves the correct character on the view page', function () {

        livewire(ViewCharacter::class, [
            'record' => $this->character->getRouteKey(),
        ])
            ->assertSuccessful()
            ->assertSee($this->character->Name)
            ->assertSee($this->character->Name)
            ->assertSee($this->character->Class)
            ->assertSee($this->character->ResetCount)
            ->assertSee($this->character->cLevel)
            ->assertSee($this->character->Strength)
            ->assertSee($this->character->Dexterity)
            ->assertSee($this->character->Vitality)
            ->assertSee($this->character->Energy)
            ->assertSee($this->character->Leadership)
            ->assertSee($this->character->MapNumber)
            ->assertSee($this->character->MapPosX)
            ->assertSee($this->character->MapPosY)
            ->assertSee($this->character->PkLevel)
            ->assertSee($this->character->PkCount)
            ->assertSee($this->character->PkTime);
    });
});

describe('Create & Delete restrictions', function () {
    it('returns false on canCreate', function () {
        $result = CharacterResource::canCreate();

        $this->assertFalse($result);
    });

    it('returns false on canDelete', function () {

        $result = CharacterResource::canDelete($this->character);

        $this->assertFalse($result);
    });
});

describe('Edit', function () {
    it('can validate input', function () {
        livewire(EditCharacter::class, [
            'record' => $this->character->getRouteKey(),
        ])
            ->fillForm([
                'Name' => null,
            ])
            ->call('save')
            ->assertHasFormErrors(['Name' => 'required']);
    });

    it('can save', function () {
        livewire(EditCharacter::class, [
            'record' => $this->character->getRouteKey(),
        ])
            ->fillForm([
                'Class' => CharacterClass::BladeMaster,
                'ResetCount' => 10,
                'cLevel' => 200,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($this->character->refresh())
            ->Class->toBe(CharacterClass::BladeMaster)
            ->ResetCount->toBe(10)
            ->cLevel->toBe(200);
    });
});

describe('Global Search', function () {
    it('returns the correct globally searchable attributes', function () {
        $expectedAttributes = ['Name', 'Class'];

        expect(CharacterResource::getGloballySearchableAttributes())
            ->toBe($expectedAttributes);
    });

    it('returns the correct global search result title', function () {
        $character = new Character([
            'Name' => 'Char',
            'Class' => CharacterClass::DarkWizard,
        ]);

        $expectedTitle = 'Char (Dark Wizard)';

        expect(CharacterResource::getGlobalSearchResultTitle($character))
            ->toBe($expectedTitle);

        $character->Class = 16;
        $expectedTitle = 'Char (Dark Knight)';

        expect(CharacterResource::getGlobalSearchResultTitle($character))
            ->toBe($expectedTitle);
    });
});
