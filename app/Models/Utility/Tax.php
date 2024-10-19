<?php

namespace App\Models\Utility;

use App\Enums\Utility\OperationType;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['operation', 'rate', 'is_flat_rate'];

    protected $casts = [
        'operation' => OperationType::class,
        'is_flat_rate' => 'boolean',
    ];

    public static function getRateFor(OperationType $operation): float
    {
        return static::where('operation', $operation)
            ->value('rate') ?? 0.0;
    }
}
