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
            self::All => 'images/characters/avatars/avatar.jpg',
            self::Knights => 'images/characters/avatars/dk.jpg',
            self::Wizards => 'images/characters/avatars/dw.jpg',
            self::Elves => 'images/characters/avatars/elf.jpg',
            self::Gladiators => 'images/characters/avatars/mg.jpg',
            self::Lords => 'images/characters/avatars/dl.jpg',
            //            self::Summoners => 'images/characters/avatars/sum.jpg',
            //            self::Fighters => 'images/characters/avatars/rf.jpg',
        };
    }
}
