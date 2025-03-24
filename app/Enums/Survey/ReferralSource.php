<?php

namespace App\Enums\Survey;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReferralSource: string implements HasColor, HasLabel
{
    case Google = 'google';
    case Friend = 'friend';
    case Facebook = 'facebook';
    case YouTube = 'youtube';
    case MMOTopSite = 'mmo_top_site';
    case MUOnlineForum = 'mu_online_forum';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Google => __('Google Search'),
            self::Friend => __('Friend Recommendation'),
            self::Facebook => __('Facebook'),
            self::YouTube => __('YouTube'),
            self::MMOTopSite => __('MMO Listing Site'),
            self::MUOnlineForum => __('MU Online Forum'),
            self::Other => __('Other Source'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Google => Color::Red,
            self::Friend => Color::Blue,
            self::Facebook => Color::Indigo,
            self::YouTube => Color::Rose,
            self::MMOTopSite => Color::Green,
            self::MUOnlineForum => Color::Purple,
            self::Other => Color::Gray,
        };
    }

    /**
     * Get chart background color with transparency
     */
    public function getChartBackgroundColor(): string
    {
        return match ($this) {
            self::Google => 'rgba(234, 67, 53, 0.5)',
            self::Friend => 'rgba(66, 133, 244, 0.5)',
            self::Facebook => 'rgba(59, 89, 152, 0.5)',
            self::YouTube => 'rgba(255, 0, 0, 0.5)',
            self::MMOTopSite => 'rgba(76, 175, 80, 0.5)',
            self::MUOnlineForum => 'rgba(156, 39, 176, 0.5)',
            self::Other => 'rgba(158, 158, 158, 0.5)',
        };
    }

    /**
     * Get chart border color
     */
    public function getChartBorderColor(): string
    {
        return match ($this) {
            self::Google => 'rgb(234, 67, 53)',
            self::Friend => 'rgb(66, 133, 244)',
            self::Facebook => 'rgb(59, 89, 152)',
            self::YouTube => 'rgb(255, 0, 0)',
            self::MMOTopSite => 'rgb(76, 175, 80)',
            self::MUOnlineForum => 'rgb(156, 39, 176)',
            self::Other => 'rgb(158, 158, 158)',
        };
    }
}
