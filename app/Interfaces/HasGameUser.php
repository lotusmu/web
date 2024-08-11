<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface HasGameUser
{
    public function gameUser(): HasOne;

    public function getRawPassword(): ?string;
}
