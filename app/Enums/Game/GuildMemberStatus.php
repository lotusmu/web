<?php

namespace App\Enums\Game;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GuildMemberStatus: int implements HasColor, HasLabel
{
    case GuildMaster = 128;
    case AssistantGuildMaster = 64;
    case BattleMaster = 32;
    case GuildMember = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::GuildMaster => 'Guild Master',
            self::AssistantGuildMaster => 'Assistant Guild Master',
            self::BattleMaster => 'Battle Master',
            self::GuildMember => 'Guild Member'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::GuildMaster => Color::Red,
            self::AssistantGuildMaster => Color::Orange,
            self::BattleMaster => Color::Sky,
            self::GuildMember => null,
        };
    }
}
