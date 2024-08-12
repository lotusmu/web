<?php

namespace App\Filament\Columns;

use Closure;
use Filament\Tables\Columns\Column;

class CharacterClassColumn extends Column
{
    protected string $view = 'components.filament.tables.columns.character-class-column';

    protected int|Closure|null $imageSize = 40;

    public function imageSize(int|Closure|null $size): static
    {
        $this->imageSize = $size;
        return $this;
    }

    public function getImageSize(): int
    {
        return $this->evaluate($this->imageSize) ?? 40;
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        return $this->evaluate($state);
    }

    public function getExtraAttributes(): array
    {
        return array_merge(parent::getExtraAttributes(), [
            'imageSize' => $this->getImageSize(),
        ]);
    }
}
