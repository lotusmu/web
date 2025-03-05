<?php

namespace App\Enums\Content;

enum DownloadProvider: string
{
    case GOOGLE_DRIVE = 'google_drive';
    case MEGA = 'mega';
    case MEDIAFIRE = 'mediafire';
    case YANDEX = 'yandex';
    case DEFAULT = 'default';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::GOOGLE_DRIVE => 'icons.providers.google-drive',
            self::MEGA => 'icons.providers.mega',
            self::MEDIAFIRE => 'icons.providers.mediafire',
            self::YANDEX => 'icons.providers.yandex-disk',
            self::DEFAULT => 'heroicon-o-cloud-arrow-down',
        };
    }

    public static function fromUrl(string $url): self
    {
        return match (true) {
            str_contains($url, 'drive.google.com') => self::GOOGLE_DRIVE,
            str_contains($url, 'mega.nz') => self::MEGA,
            str_contains($url, 'mediafire.com') => self::MEDIAFIRE,
            str_contains($url, 'disk.yandex.com') => self::YANDEX,
            default => self::DEFAULT
        };
    }
}
