<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CharacterClass: int implements HasLabel
{
    case DarkWizard = 0;
    case SoulMaster = 1;
    case GrandMaster = 3;
    case SoulWizard = 7;
    case DarkKnight = 16;
    case BladeKnight = 17;
    case BladeMaster = 19;
    case DragonKnight = 23;
    case FairyElf = 32;
    case MuseElf = 33;
    case HighElf = 35;
    case NobleElf = 39;
    case MagicGladiator = 48;
    case DuelMaster = 50;
    case MagicKnight = 54;
    case DarkLord = 64;
    case LordEmperor = 66;
    case EmpireLord = 70;
    case Summoner = 80;
    case BloodySummoner = 81;
    case DimensionMaster = 83;
    case DimensionSummoner = 87;
    case RageFighter = 96;
    case FistMaster = 98;
    case FistBlazer = 102;

    public function getLabel(): string
    {
        return match ($this) {
            self::DarkWizard => 'Dark Wizard',
            self::SoulMaster => 'Soul Master',
            self::GrandMaster => 'Grand Master',
            self::SoulWizard => 'Soul Wizard',
            self::DarkKnight => 'Dark Knight',
            self::BladeKnight => 'Blade Knight',
            self::BladeMaster => 'Blade Master',
            self::DragonKnight => 'Dragon Knight',
            self::FairyElf => 'Fairy Elf',
            self::MuseElf => 'Muse Elf',
            self::HighElf => 'High Elf',
            self::NobleElf => 'Noble Elf',
            self::MagicGladiator => 'Magic Gladiator',
            self::DuelMaster => 'Duel Master',
            self::MagicKnight => 'Magic Knight',
            self::DarkLord => 'Dark Lord',
            self::LordEmperor => 'Lord Emperor',
            self::EmpireLord => 'Empire Lord',
            self::Summoner => 'Summoner',
            self::BloodySummoner => 'Bloody Summoner',
            self::DimensionMaster => 'Dimension Master',
            self::DimensionSummoner => 'Dimension Summoner',
            self::RageFighter => 'Rage Fighter',
            self::FistMaster => 'Fist Master',
            self::FistBlazer => 'Fist Blazer',
        };
    }

    public function getImagePath(): string
    {
        return match ($this) {
            self::DarkWizard, self::SoulMaster, self::GrandMaster, self::SoulWizard => 'images/character_classes/dw.jpg',
            self::DarkKnight, self::BladeKnight, self::BladeMaster, self::DragonKnight => 'images/character_classes/dk.jpg',
            self::FairyElf, self::MuseElf, self::HighElf, self::NobleElf => 'images/character_classes/elf.jpg',
            self::MagicGladiator, self::DuelMaster, self::MagicKnight => 'images/character_classes/mg.jpg',
            self::DarkLord, self::LordEmperor, self::EmpireLord => 'images/character_classes/dl.jpg',
            self::Summoner, self::BloodySummoner, self::DimensionMaster, self::DimensionSummoner => 'images/character_classes/sum.jpg',
            self::RageFighter, self::FistMaster, self::FistBlazer => 'images/character_classes/rf.jpg',
        };
    }
}
