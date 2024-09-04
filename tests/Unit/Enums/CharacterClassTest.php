<?php

use App\Enums\Game\CharacterClass;
use Filament\Support\Contracts\HasLabel;

it('has the correct number of enum cases', function () {
    expect(CharacterClass::cases())->toHaveCount(36);
});

it('has the correct enum values', function () {
    expect(CharacterClass::DarkWizard->value)->toBe(0)
        ->and(CharacterClass::SoulMaster->value)->toBe(1)
        ->and(CharacterClass::GrandMaster->value)->toBe(2)
        ->and(CharacterClass::GrandMaster2->value)->toBe(3)
        ->and(CharacterClass::SoulWizard->value)->toBe(7)
        ->and(CharacterClass::DarkKnight->value)->toBe(16)
        ->and(CharacterClass::BladeKnight->value)->toBe(17)
        ->and(CharacterClass::BladeMaster->value)->toBe(18)
        ->and(CharacterClass::BladeMaster2->value)->toBe(19)
        ->and(CharacterClass::DragonKnight->value)->toBe(23)
        ->and(CharacterClass::FairyElf->value)->toBe(32)
        ->and(CharacterClass::MuseElf->value)->toBe(33)
        ->and(CharacterClass::HighElf->value)->toBe(34)
        ->and(CharacterClass::HighElf2->value)->toBe(35)
        ->and(CharacterClass::NobleElves->value)->toBe(39)
        ->and(CharacterClass::MagicGladiator->value)->toBe(48)
        ->and(CharacterClass::DuelMaster->value)->toBe(49)
        ->and(CharacterClass::DuelMaster2->value)->toBe(50)
        ->and(CharacterClass::MagicKnight->value)->toBe(51)
        ->and(CharacterClass::MagicKnight2->value)->toBe(54)
        ->and(CharacterClass::DarkLord->value)->toBe(64)
        ->and(CharacterClass::LordEmperor->value)->toBe(65)
        ->and(CharacterClass::LordEmperor2->value)->toBe(66)
        ->and(CharacterClass::EmpireRoad->value)->toBe(67)
        ->and(CharacterClass::EmpireRoad2->value)->toBe(70)
        ->and(CharacterClass::Summoner->value)->toBe(80)
        ->and(CharacterClass::BloodySummoner->value)->toBe(81)
        ->and(CharacterClass::DimensionMaster->value)->toBe(82)
        ->and(CharacterClass::DimensionMaster2->value)->toBe(83)
        ->and(CharacterClass::DimensionSummoner->value)->toBe(84)
        ->and(CharacterClass::DimensionSummoner2->value)->toBe(87)
        ->and(CharacterClass::RageFighter->value)->toBe(96)
        ->and(CharacterClass::FistMaster->value)->toBe(97)
        ->and(CharacterClass::FistMaster2->value)->toBe(98)
        ->and(CharacterClass::FistBlazer->value)->toBe(99)
        ->and(CharacterClass::FistBlazer2->value)->toBe(102);
});

it('implements necessary interfaces', function () {
    $interfaces = class_implements(CharacterClass::class);
    expect($interfaces)->toContain(HasLabel::class);
});

it('returns the correct label for each class', function () {
    expect(CharacterClass::DarkWizard->getLabel())->toBe('Dark Wizard')
        ->and(CharacterClass::SoulMaster->getLabel())->toBe('Soul Master')
        ->and(CharacterClass::GrandMaster->getLabel())->toBe('Grand Master')
        ->and(CharacterClass::GrandMaster2->getLabel())->toBe('Grand Master')
        ->and(CharacterClass::SoulWizard->getLabel())->toBe('Soul Wizard')
        ->and(CharacterClass::DarkKnight->getLabel())->toBe('Dark Knight')
        ->and(CharacterClass::BladeKnight->getLabel())->toBe('Blade Knight')
        ->and(CharacterClass::BladeMaster->getLabel())->toBe('Blade Master')
        ->and(CharacterClass::BladeMaster2->getLabel())->toBe('Blade Master')
        ->and(CharacterClass::DragonKnight->getLabel())->toBe('Dragon Knight')
        ->and(CharacterClass::FairyElf->getLabel())->toBe('Fairy Elf')
        ->and(CharacterClass::MuseElf->getLabel())->toBe('Muse Elf')
        ->and(CharacterClass::HighElf->getLabel())->toBe('High Elf')
        ->and(CharacterClass::HighElf2->getLabel())->toBe('High Elf')
        ->and(CharacterClass::NobleElves->getLabel())->toBe('Noble Elves')
        ->and(CharacterClass::MagicGladiator->getLabel())->toBe('Magic Gladiator')
        ->and(CharacterClass::DuelMaster->getLabel())->toBe('Duel Master')
        ->and(CharacterClass::DuelMaster2->getLabel())->toBe('Duel Master')
        ->and(CharacterClass::MagicKnight->getLabel())->toBe('Magic Knight')
        ->and(CharacterClass::MagicKnight2->getLabel())->toBe('Magic Knight')
        ->and(CharacterClass::DarkLord->getLabel())->toBe('Dark Lord')
        ->and(CharacterClass::LordEmperor->getLabel())->toBe('Lord Emperor')
        ->and(CharacterClass::LordEmperor2->getLabel())->toBe('Lord Emperor')
        ->and(CharacterClass::EmpireRoad->getLabel())->toBe('Empire Road')
        ->and(CharacterClass::EmpireRoad2->getLabel())->toBe('Empire Road')
        ->and(CharacterClass::Summoner->getLabel())->toBe('Summoner')
        ->and(CharacterClass::BloodySummoner->getLabel())->toBe('Bloody Summoner')
        ->and(CharacterClass::DimensionMaster->getLabel())->toBe('Dimension Master')
        ->and(CharacterClass::DimensionMaster2->getLabel())->toBe('Dimension Master')
        ->and(CharacterClass::DimensionSummoner->getLabel())->toBe('Dimension Summoner')
        ->and(CharacterClass::DimensionSummoner2->getLabel())->toBe('Dimension Summoner')
        ->and(CharacterClass::RageFighter->getLabel())->toBe('Rage Fighter')
        ->and(CharacterClass::FistMaster->getLabel())->toBe('Fist Master')
        ->and(CharacterClass::FistMaster2->getLabel())->toBe('Fist Master')
        ->and(CharacterClass::FistBlazer->getLabel())->toBe('Fist Blazer')
        ->and(CharacterClass::FistBlazer2->getLabel())->toBe('Fist Blazer');
});

it('returns the correct image path for each class group', function () {
    $wizardClasses = [
        CharacterClass::DarkWizard, CharacterClass::SoulMaster, CharacterClass::GrandMaster,
        CharacterClass::GrandMaster2, CharacterClass::SoulWizard,
    ];
    $knightClasses = [
        CharacterClass::DarkKnight, CharacterClass::BladeKnight, CharacterClass::BladeMaster,
        CharacterClass::BladeMaster2, CharacterClass::DragonKnight,
    ];
    $elfClasses = [
        CharacterClass::FairyElf, CharacterClass::MuseElf, CharacterClass::HighElf,
        CharacterClass::HighElf2, CharacterClass::NobleElves,
    ];
    $gladiatorClasses = [
        CharacterClass::MagicGladiator, CharacterClass::DuelMaster, CharacterClass::DuelMaster2,
        CharacterClass::MagicKnight, CharacterClass::MagicKnight2,
    ];
    $lordClasses = [
        CharacterClass::DarkLord, CharacterClass::LordEmperor, CharacterClass::LordEmperor2,
        CharacterClass::EmpireRoad, CharacterClass::EmpireRoad2,
    ];
    $summonerClasses = [
        CharacterClass::Summoner, CharacterClass::BloodySummoner, CharacterClass::DimensionMaster,
        CharacterClass::DimensionMaster2, CharacterClass::DimensionSummoner, CharacterClass::DimensionSummoner2,
    ];
    $fighterClasses = [
        CharacterClass::RageFighter, CharacterClass::FistMaster, CharacterClass::FistMaster2,
        CharacterClass::FistBlazer, CharacterClass::FistBlazer2,
    ];

    foreach ($wizardClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/dw.jpg');
    }
    foreach ($knightClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/dk.jpg');
    }
    foreach ($elfClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/elf.jpg');
    }
    foreach ($gladiatorClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/mg.jpg');
    }
    foreach ($lordClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/dl.jpg');
    }
    foreach ($summonerClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/sum.jpg');
    }
    foreach ($fighterClasses as $class) {
        expect($class->getImagePath())->toBe('images/character_classes/rf.jpg');
    }
});
