<?php

namespace App\Actions\Stream;

use App\Enums\Partner\PartnerStatus;
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
                return StreamSession::select([
                    'stream_sessions.id',
                    'stream_sessions.partner_id',
                    'stream_sessions.channel_name',
                    'stream_sessions.title',
                    'stream_sessions.game_category',
                    'stream_sessions.average_viewers',
                    'stream_sessions.started_at',
                ])
                    ->join('partners', 'stream_sessions.partner_id', '=', 'partners.id')
                    ->where('partners.status', PartnerStatus::ACTIVE)
                    ->whereNull('stream_sessions.ended_at')
                    ->orderByDesc('stream_sessions.average_viewers')
                    ->get();
            } finally {
                Cache::forget('active-streams-loading');
            }
        });
    }
}
