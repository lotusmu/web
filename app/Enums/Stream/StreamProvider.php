<?php

namespace App\Enums\Stream;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StreamProvider: string implements HasColor, HasIcon, HasLabel
{
    case TWITCH = 'twitch';
    case YOUTUBE = 'youtube';
    case FACEBOOK = 'facebook';

    public function getLabel(): string
    {
        return match ($this) {
            self::TWITCH => 'Twitch',
            self::YOUTUBE => 'YouTube',
            self::FACEBOOK => 'Facebook',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TWITCH => 'purple',
            self::YOUTUBE => 'red',
            self::FACEBOOK => 'blue',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TWITCH => 'heroicon-o-video-camera',
            self::YOUTUBE => 'heroicon-o-play',
            self::FACEBOOK => 'heroicon-o-users',
        };
    }

    public function getApiBaseUrl(): string
    {
        return match ($this) {
            self::TWITCH => 'https://api.twitch.tv/helix',
            self::YOUTUBE => 'https://www.googleapis.com/youtube/v3',
            self::FACEBOOK => 'https://graph.facebook.com/v18.0',
        };
    }

    public function getEmbedUrl(string $channelName): string
    {
        return match ($this) {
            self::TWITCH => "https://player.twitch.tv/?channel={$channelName}&parent=".parse_url(config('app.url'), PHP_URL_HOST),
            self::YOUTUBE => "https://www.youtube.com/embed/live_stream?channel={$channelName}",
            self::FACEBOOK => "https://www.facebook.com/plugins/video.php?href={$channelName}",
        };
    }
}
