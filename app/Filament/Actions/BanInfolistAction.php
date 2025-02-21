<?php

namespace App\Filament\Actions;

use App\Filament\Actions\Concerns\BannableAction;
use Filament\Infolists\Components\Actions\Action as InfolistAction;

class BanInfolistAction extends InfolistAction
{
    use BannableAction;

    public static function make(?string $name = null): static
    {
        $static = parent::make($name ?? 'ban');

        return $static->configureAction();
    }
}
