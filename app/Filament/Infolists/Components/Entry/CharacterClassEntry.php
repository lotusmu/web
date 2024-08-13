<?php

namespace App\Filament\Infolists\Components\Entry;

use Closure;
use Filament\Infolists\Components\Entry;

class CharacterClassEntry extends Entry
{
    protected string $view = 'filament.infolists.entries.character-class';

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
            return CharacterClassEntry::from($state);
        }
        return $state;
    }
}
