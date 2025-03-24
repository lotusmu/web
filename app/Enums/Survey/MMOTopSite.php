<?php

namespace App\Enums\Survey;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MMOTopSite: string implements HasColor, HasLabel
{
    case MMOAnons = 'mmoanons';
    case MMOTopRu = 'mmotop_ru';
    case ServeraMU = 'servera_mu';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::MMOAnons => __('MMOAnons.com'),
            self::MMOTopRu => __('MMOTop.ru'),
            self::ServeraMU => __('Servera-MU.online'),
            self::Other => __('Other MMO Site'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MMOAnons => Color::Emerald,
            self::MMOTopRu => Color::Sky,
            self::ServeraMU => Color::Amber,
            self::Other => Color::Gray,
        };
    }
}
