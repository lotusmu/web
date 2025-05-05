<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use Illuminate\Database\Eloquent\Model;

class HallOfFame extends Model
{
    use GameConnection;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'HallOfFame';

    protected $fillable = [
        'dk',
        'dw',
        'fe',
        'mg',
        'dl',
    ];
}
