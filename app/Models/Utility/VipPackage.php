<?php

namespace App\Models\Utility;

use App\Enums\Game\AccountLevel;
use Illuminate\Database\Eloquent\Model;

class VipPackage extends Model
{
    protected $fillable = ['level', 'is_best_value', 'duration', 'cost'];

    protected $casts = [
        'level' => AccountLevel::class,
        'is_best_value' => 'boolean',
    ];
}
