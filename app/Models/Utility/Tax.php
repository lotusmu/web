<?php

namespace App\Models\Utility;

use App\Enums\Utility\OperationType;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['operation', 'rate'];

    protected $casts = [
        'operation' => OperationType::class,
    ];

    public static function getRateFor(OperationType $operation): float
    {
        return static::where('operation', $operation)
            ->value('rate') ?? 0.0;
    }
}
