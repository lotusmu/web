<?php

namespace App\Models\Utility;

use App\Enums\Game\ServerVersion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GameServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'connection_name',
        'port',
        'experience_rate',
        'drop_rate',
        'is_active',
        'server_version',
        'max_resets',
        'starting_resets',
        'reset_zen',
        'clear_pk_zen',
        'online_multiplier',
        'launch_date',
        'is_default',
    ];

    protected $casts = [
        'launch_date' => 'datetime',
        'is_default' => 'boolean',
        'server_version' => ServerVersion::class,
    ];

    public function getServerName(): string
    {
        return "{$this->name} - x{$this->experience_rate}";
    }

    public function getStatus(): array
    {
        return Cache::get("server_status_{$this->id}", [
            'is_online' => false,
            'online_count' => 0,
            'multiplied_count' => 0,
            'last_updated' => now()->subHours(1),
        ]);
    }

    public function isOnline(): bool
    {
        return $this->getStatus()['is_online'];
    }

    public function getOnlineCount(): int
    {
        return $this->getStatus()['online_count'];
    }

    public function getMultipliedCount(): int
    {
        return $this->getStatus()['multiplied_count'];
    }

    public static function default(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
