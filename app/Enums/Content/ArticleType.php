<?php

namespace App\Enums\Content;

use Filament\Support\Contracts\HasLabel;

enum ArticleType: string implements HasLabel
{
    case NEWS = 'news';
    case PATCH_NOTE = 'patch_note';

    public function getLabel(): string
    {
        return match ($this) {
            self::NEWS => 'News',
            self::PATCH_NOTE => 'Patch Note',
        };
    }
}
