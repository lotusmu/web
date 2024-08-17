<?php

use App\Enums\CharacterClass;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Pages\EditCharacter;
use App\Filament\Resources\CharacterResource\Pages\ViewCharacter;
use App\Models\Character;

use function Pest\Livewire\livewire;

describe('Render', function () {
    it('can render index page', function () {
        $this->get(CharacterResource::getUrl('index'))
            ->assertSuccessful();
    });

    it('can render edit page', function () {
        $character = createCharacter();

        $this->get(CharacterResource::getUrl('edit', [$character]))
            ->assertSuccessful();
    });

    it('can retrieve data on edit page', function () {
        $character = createCharacter();

        livewire(EditCharacter::class, [
            'record' => $character->getRouteKey(),
        ])
            ->assertFormSet([
                'Name' => $character->Name,
                'Class' => $character->Class->value,
                'ResetCount' => $character->ResetCount,
                'cLevel' => $character->cLevel,
                'Strength' => $character->Strength,
                'Dexterity' => $character->Dexterity,
                'Vitality' => $character->Vitality,
                'Energy' => $character->Energy,
                'Leadership' => $character->Leadership,
                'MapNumber' => $character->MapNumber->value,
                'MapPosX' => $character->MapPosX,
                'MapPosY' => $character->MapPosY,
                'PkLevel' => $character->PkLevel->value,
                'PkCount' => $character->PkCount,
                'PkTime' => $character->PkTime,
            ]);
    });

    it('can render view page', function () {
        $character = createCharacter();

        $this->get(CharacterResource::getUrl('view', [$character]))
            ->assertSuccessful();
    });

    it('can retrieves the correct character on the view page', function () {
        $character = createCharacter();

        livewire(ViewCharacter::class, [
            'record' => $character->getRouteKey(),
        ])
            ->assertSuccessful()
            ->assertSee($character->Name)
            ->assertSee($character->Name)
            ->assertSee($character->Class)
            ->assertSee($character->ResetCount)
            ->assertSee($character->cLevel)
            ->assertSee($character->Strength)
            ->assertSee($character->Dexterity)
            ->assertSee($character->Vitality)
            ->assertSee($character->Energy)
            ->assertSee($character->Leadership)
            ->assertSee($character->MapNumber)
            ->assertSee($character->MapPosX)
            ->assertSee($character->MapPosY)
            ->assertSee($character->PkLevel)
            ->assertSee($character->PkCount)
            ->assertSee($character->PkTime);
    });
});

describe('Create & Delete restrictions', function () {
    it('returns false on canCreate', function () {
        $result = CharacterResource::canCreate();

        $this->assertFalse($result);
    });

    it('returns false on canDelete', function () {
        $character = createCharacter();

        $result = CharacterResource::canDelete($character);

        $this->assertFalse($result);
    });
});

describe('Edit', function () {
    it('can validate input', function () {
        $character = createCharacter();

        livewire(EditCharacter::class, [
            'record' => $character->getRouteKey(),
        ])
            ->fillForm([
                'Name' => null,
            ])
            ->call('save')
            ->assertHasFormErrors(['Name' => 'required']);
    });

    it('can save', function () {
        $character = createCharacter();

        livewire(EditCharacter::class, [
            'record' => $character->getRouteKey(),
        ])
            ->fillForm([
                'Class' => CharacterClass::BladeMaster,
                'ResetCount' => 10,
                'cLevel' => 200,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($character->refresh())
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
