<?php

namespace App\Filament\Actions;

use App\Filament\Actions\Concerns\BannableAction;
use Filament\Tables\Actions\Action as TableAction;

class BanTableAction extends TableAction
{
    use BannableAction;

    public static function make(?string $name = null): static
    {
        $static = parent::make($name ?? 'ban');

        return $static->configureAction();
    }
}
