<?php

namespace App\Models\Utility;

use App\Models\Game\Status;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Log;

class GameServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'connection_name',
        'experience_rate',
        'drop_rate',
        'is_active',
        'server_version',
        'max_resets',
        'starting_resets',
        'reset_zen',
        'clear_pk_zen',
        'online_multiplier',
    ];

    public function getServerName(): string
    {
        return "{$this->name} - x{$this->experience_rate}";
    }

    public function isOnline(): bool
    {
        $cacheKey = "server_status_{$this->id}";

        try {
            $isOnline = (bool) @fsockopen(
                config("database.connections.{$this->connection_name}.host"),
                config('gameserver.port'),
                $errno,
                $errstr,
                config('gameserver.socket_timeout')
            );

            cache()->put($cacheKey, $isOnline,
                now()->addMinutes(config('gameserver.cache.status_ttl'))
            );

            return $isOnline;

        } catch (ErrorException $e) {
            Log::error("Socket connection failed for {$this->name}: {$e->getMessage()}");

            return cache()->get($cacheKey, false);
        }
    }

    public function getMultipliedCount(): int
    {
        return ceil($this->getOnlineCount() * $this->online_multiplier);
    }

    public function getOnlineCount(): int
    {
        $cacheKey = "server_online_count_{$this->id}";

        try {
            return cache()->remember($cacheKey,
                now()->addMinutes(config('gameserver.cache.count_ttl')),
                function () {
                    try {
                        return Status::on($this->connection_name)
                            ->where('ConnectStat', 1)
                            ->count();
                    } catch (QueryException $e) {
                        Log::error("Query failed for {$this->name}: {$e->getMessage()}");

                        return 0;
                    }
                }
            );
        } catch (Exception $e) {
            Log::error("Cache failed for {$this->name}: {$e->getMessage()}");

            return 0;
        }
    }
}
