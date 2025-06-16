<?php

namespace App\Enums\Partner;

use Filament\Support\Contracts\HasLabel;

enum Platform: string implements HasLabel
{
    case YOUTUBE = 'youtube';
    case TWITCH = 'twitch';
    case FACEBOOK = 'facebook';

    public function getLabel(): string
    {
        return match ($this) {
            self::YOUTUBE => 'YouTube',
            self::TWITCH => 'Twitch',
            self::FACEBOOK => 'Facebook',
        };
    }
}
