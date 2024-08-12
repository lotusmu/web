<?php

namespace App\Filament\Infolists\Components;

use App\Enums\CharacterClass;
use Closure;
use Filament\Infolists\Components\Entry;

class CharacterClassEntry extends Entry
{
    protected string $view = 'components.filament.infolists.character-class-entry';

    protected int|Closure|null $imageSize = 64;

    public function imageSize(int|Closure|null $size): static
    {
        $this->imageSize = $size;
        return $this;
    }

    public function getImageSize(): int
    {
        return $this->evaluate($this->imageSize);
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        if (is_int($state)) {
            return CharacterClass::from($state);
        }
        return $state;
    }
}
