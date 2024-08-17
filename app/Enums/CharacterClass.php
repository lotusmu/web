<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CharacterClass: int implements HasLabel
{
    case DarkWizard = 0;
    case SoulMaster = 1;
    case GrandMaster = 2;
    case GrandMaster2 = 3;
    case SoulWizard = 7;
    case DarkKnight = 16;
    case BladeKnight = 17;
    case BladeMaster = 18;
    case BladeMaster2 = 19;
    case DragonKnight = 23;
    case FairyElf = 32;
    case MuseElf = 33;
    case HighElf = 34;
    case HighElf2 = 35;
    case NobleElves = 39;
    case MagicGladiator = 48;
    case DuelMaster = 49;
    case DuelMaster2 = 50;
    case MagicKnight = 51;
    case MagicKnight2 = 54;
    case DarkLord = 64;
    case LordEmperor = 65;
    case LordEmperor2 = 66;
    case EmpireRoad = 67;
    case EmpireRoad2 = 70;
    case Summoner = 80;
    case BloodySummoner = 81;
    case DimensionMaster = 82;
    case DimensionMaster2 = 83;
    case DimensionSummoner = 84;
    case DimensionSummoner2 = 87;
    case RageFighter = 96;
    case FistMaster = 97;
    case FistMaster2 = 98;
    case FistBlazer = 99;
    case FistBlazer2 = 102;

    public function getLabel(): string
    {
        return match ($this) {
            self::DarkWizard => 'Dark Wizard',
            self::SoulMaster => 'Soul Master',
            self::GrandMaster, self::GrandMaster2 => 'Grand Master',
            self::SoulWizard => 'Soul Wizard',
            self::DarkKnight => 'Dark Knight',
            self::BladeKnight => 'Blade Knight',
            self::BladeMaster, self::BladeMaster2 => 'Blade Master',
            self::DragonKnight => 'Dragon Knight',
            self::FairyElf => 'Fairy Elf',
            self::MuseElf => 'Muse Elf',
            self::HighElf, self::HighElf2 => 'High Elf',
            self::NobleElves => 'Noble Elves',
            self::MagicGladiator => 'Magic Gladiator',
            self::DuelMaster, self::DuelMaster2 => 'Duel Master',
            self::MagicKnight, self::MagicKnight2 => 'Magic Knight',
            self::DarkLord => 'Dark Lord',
            self::LordEmperor, self::LordEmperor2 => 'Lord Emperor',
            self::EmpireRoad, self::EmpireRoad2 => 'Empire Road',
            self::Summoner => 'Summoner',
            self::BloodySummoner => 'Bloody Summoner',
            self::DimensionMaster, self::DimensionMaster2 => 'Dimension Master',
            self::DimensionSummoner, self::DimensionSummoner2 => 'Dimension Summoner',
            self::RageFighter => 'Rage Fighter',
            self::FistMaster, self::FistMaster2 => 'Fist Master',
            self::FistBlazer, self::FistBlazer2 => 'Fist Blazer',
        };
    }

    public function getImagePath(): string
    {
        return match ($this) {
            self::DarkWizard, self::SoulMaster, self::GrandMaster, self::GrandMaster2, self::SoulWizard => 'images/character_classes/dw.jpg',
            self::DarkKnight, self::BladeKnight, self::BladeMaster, self::BladeMaster2, self::DragonKnight => 'images/character_classes/dk.jpg',
            self::FairyElf, self::MuseElf, self::HighElf, self::HighElf2, self::NobleElves => 'images/character_classes/elf.jpg',
            self::MagicGladiator, self::DuelMaster, self::DuelMaster2, self::MagicKnight, self::MagicKnight2 => 'images/character_classes/mg.jpg',
            self::DarkLord, self::LordEmperor, self::LordEmperor2, self::EmpireRoad, self::EmpireRoad2 => 'images/character_classes/dl.jpg',
            self::Summoner, self::BloodySummoner, self::DimensionMaster, self::DimensionMaster2, self::DimensionSummoner, self::DimensionSummoner2 => 'images/character_classes/sum.jpg',
            self::RageFighter, self::FistMaster, self::FistMaster2, self::FistBlazer, self::FistBlazer2 => 'images/character_classes/rf.jpg',
        };
    }
}
