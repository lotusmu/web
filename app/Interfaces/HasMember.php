<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface HasMember
{
    public function member(): HasOne;

    public function getRawPassword(): ?string;
}
