<?php

namespace App\Actions\Stream;

use App\Models\Stream\StreamSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LoadActiveStreamsAction
{
    public function handle(): Collection
    {
        // Prevent cache stampede
        if (Cache::has('active-streams-loading')) {
            return Cache::get('active-streams-public', collect());
        }

        return Cache::remember('active-streams-public', 60, function () {
            Cache::put('active-streams-loading', true, 10);

            try {
                return StreamSession::select(['id', 'partner_id', 'channel_name', 'title', 'game_category', 'average_viewers', 'started_at'])
                    ->whereNull('ended_at')
                    ->orderByDesc('average_viewers')
                    ->get();
            } finally {
                Cache::forget('active-streams-loading');
            }
        });
    }
}
