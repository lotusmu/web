<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Solutionforest\FilamentEmail2fa\Pages\TwoFactorAuth as BaseTwoFactorAuth;

class TwoFactorAuth extends BaseTwoFactorAuth
{
    protected static $maxWidth = 'sm';

    public function getUser()
    {
        return Filament::auth()->user();
    }
}
