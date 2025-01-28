<?php

namespace App\Enums\Utility;

use App\Enums\Game\CharacterClass;

enum FilterCharacterClass: string
{
    case All = 'all';
    case Knights = 'knights';
    case Wizards = 'wizards';
    case Elves = 'elves';
    case Gladiators = 'gladiators';
    case Lords = 'lords';
    //    case Summoners = 'summoners';
    //    case Fighters = 'fighters';

    public function getLabel(): string
    {
        return match ($this) {
            self::All => 'All',
            self::Knights => 'Knights',
            self::Wizards => 'Wizards',
            self::Elves => 'Elves',
            self::Gladiators => 'Gladiators',
            self::Lords => 'Lords',
            //            self::Summoners => 'Summoners',
            //            self::Fighters => 'Fighters',
        };
    }

    public function getClasses(): array
    {
        return match ($this) {
            self::All => array_map(fn ($case) => $case->value, CharacterClass::cases()),

            self::Knights => [
                CharacterClass::DarkKnight->value,
                CharacterClass::BladeKnight->value,
                CharacterClass::BladeMaster->value,
                CharacterClass::BladeMaster2->value,
                CharacterClass::DragonKnight->value,
            ],
            self::Wizards => [
                CharacterClass::DarkWizard->value,
                CharacterClass::SoulMaster->value,
                CharacterClass::GrandMaster->value,
                CharacterClass::GrandMaster2->value,
                CharacterClass::SoulWizard->value,
            ],
            self::Elves => [
                CharacterClass::FairyElf->value,
                CharacterClass::MuseElf->value,
                CharacterClass::HighElf->value,
                CharacterClass::HighElf2->value,
                CharacterClass::NobleElves->value,
            ],
            self::Gladiators => [
                CharacterClass::MagicGladiator->value,
                CharacterClass::DuelMaster->value,
                CharacterClass::DuelMaster2->value,
                CharacterClass::MagicKnight->value,
                CharacterClass::MagicKnight2->value,
            ],
            self::Lords => [
                CharacterClass::DarkLord->value,
                CharacterClass::LordEmperor->value,
                CharacterClass::LordEmperor2->value,
                CharacterClass::EmpireRoad->value,
                CharacterClass::EmpireRoad2->value,
            ],
            //            self::Summoners => [
            //                CharacterClass::Summoner->value,
            //                CharacterClass::BloodySummoner->value,
            //                CharacterClass::DimensionMaster->value,
            //                CharacterClass::DimensionMaster2->value,
            //                CharacterClass::DimensionSummoner->value,
            //                CharacterClass::DimensionSummoner2->value,
            //            ],
            //            self::Fighters => [
            //                CharacterClass::RageFighter->value,
            //                CharacterClass::FistMaster->value,
            //                CharacterClass::FistMaster2->value,
            //                CharacterClass::FistBlazer->value,
            //                CharacterClass::FistBlazer2->value,
            //            ],
        };
    }

    public function getImagePath(): string
    {
        return match ($this) {
            self::All => 'images/character_classes/avatar.jpg',
            self::Knights => 'images/character_classes/dk.jpg',
            self::Wizards => 'images/character_classes/dw.jpg',
            self::Elves => 'images/character_classes/elf.jpg',
            self::Gladiators => 'images/character_classes/mg.jpg',
            self::Lords => 'images/character_classes/dl.jpg',
            //            self::Summoners => 'images/character_classes/sum.jpg',
            //            self::Fighters => 'images/character_classes/rf.jpg',
        };
    }
}
