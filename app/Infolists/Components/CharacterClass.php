<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Component;

class CharacterClass extends Component
{
    protected string $view = 'infolists.components.character-class';

    public static function make(): static
    {
        return app(static::class);
    }
}
