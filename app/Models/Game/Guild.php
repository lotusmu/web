<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guild extends Model
{
    use GameConnection;
    use HasFactory;

    protected $table = 'Guild';

    protected $primaryKey = 'G_Name';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'G_Name',
        'G_Mark',
        'G_Score',
        'G_Master',
        'G_Count',
        'G_Notice',
        'G_Type',
        'G_Rival',
        'G_Union',
        'MemberCount',
        'CS_Wins',
    ];

    protected $casts = [
        'G_Score' => 'integer',
        'G_Count' => 'integer',
        'Number' => 'integer',
        'G_Type' => 'integer',
        'G_Rival' => 'integer',
        'G_Union' => 'integer',
        'MemberCount' => 'integer',
        'CS_Wins' => 'integer',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(GuildMember::class, 'G_Name', 'G_Name');
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'G_Master', 'Name');
    }
}
