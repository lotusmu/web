<?php

namespace App\Actions\Stream;

use App\Enums\Stream\StreamProvider;
use App\Models\Partner\Partner;
use App\Models\Stream\StreamAnalytics;
use App\Models\Stream\StreamSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GenerateStreamAnalytics
{
    public function handle(?Carbon $date = null): array
    {
        $date = $date ?? today();

        $results = [
            'date' => $date->toDateString(),
            'partners_processed' => 0,
            'analytics_created' => 0,
            'analytics_updated' => 0,
        ];

        $activePartners = Partner::where('status', 'active')
            ->whereJsonContains('channels', ['platform' => 'twitch'])
            ->get();

        foreach ($activePartners as $partner) {
            $results['partners_processed']++;

            foreach (StreamProvider::cases() as $provider) {
                if ($this->partnerHasProvider($partner, $provider)) {
                    $analytics = $this->generateAnalyticsForPartner($partner, $provider, $date);

                    if ($analytics['created']) {
                        $results['analytics_created']++;
                    } else {
                        $results['analytics_updated']++;
                    }
                }
            }
        }

        return $results;
    }

    private function partnerHasProvider(Partner $partner, StreamProvider $provider): bool
    {
        return collect($partner->channels)
            ->contains('platform', $provider->value);
    }

    private function generateAnalyticsForPartner(Partner $partner, StreamProvider $provider, Carbon $date): array
    {
        // Get all sessions for this partner and provider on this date
        $sessions = StreamSession::where('partner_id', $partner->id)
            ->where('provider', $provider)
            ->whereDate('started_at', $date)
            ->get();

        if ($sessions->isEmpty()) {
            return ['created' => false];
        }

        $analytics = $this->calculateAnalytics($sessions, $date);

        // Get historical data for growth calculations
        $previousWeekAnalytics = $this->getPreviousWeekAnalytics($partner, $provider, $date);
        $analytics['viewer_growth_rate'] = $this->calculateGrowthRate(
            $analytics['total_viewers'],
            $previousWeekAnalytics?->total_viewers ?? 0
        );

        // Calculate streak data
        $analytics['longest_streak_days'] = $this->calculateLongestStreak($partner, $provider, $date);
        $analytics['days_streamed_this_week'] = $this->calculateDaysStreamedThisWeek($partner, $provider, $date);

        // Create or update analytics record
        $record = StreamAnalytics::updateOrCreate(
            [
                'partner_id' => $partner->id,
                'provider' => $provider,
                'date' => $date,
            ],
            $analytics
        );

        return ['created' => $record->wasRecentlyCreated];
    }

    private function calculateAnalytics(Collection $sessions, Carbon $date): array
    {
        $totalHours = 0;
        $totalViewers = 0;
        $streamCount = $sessions->count();

        foreach ($sessions as $session) {
            $totalHours += $session->getDurationInHours();
            $totalViewers += $session->average_viewers;
        }

        $averageDuration = $streamCount > 0 ? $totalHours / $streamCount : 0;

        return [
            'total_hours_streamed' => round($totalHours, 2),
            'total_viewers' => $totalViewers,
            'stream_count' => $streamCount,
            'average_stream_duration' => round($averageDuration, 2),
            'scheduled_vs_actual_hours' => $this->calculateScheduleAdherence($sessions, $date),
            'chat_activity_score' => $this->calculateChatActivityScore($sessions),
        ];
    }

    private function calculateScheduleAdherence(Collection $sessions, Carbon $date): float
    {
        // For now, assume partners should stream 4 hours per day
        // This could be made configurable per partner later
        $expectedHours = 4.0;
        $actualHours = $sessions->sum(fn ($session) => $session->getDurationInHours());

        return $actualHours > 0 ? min(1.0, $actualHours / $expectedHours) : 0;
    }

    private function calculateChatActivityScore(Collection $sessions): float
    {
        // Simple chat activity score based on viewer engagement
        // Higher average viewers relative to peak suggests better engagement
        $totalEngagement = 0;
        $sessionCount = $sessions->count();

        foreach ($sessions as $session) {
            if ($session->peak_viewers > 0) {
                $engagement = $session->average_viewers / $session->peak_viewers;
                $totalEngagement += $engagement;
            }
        }

        return $sessionCount > 0 ? round(($totalEngagement / $sessionCount) * 100, 2) : 0;
    }

    private function calculateGrowthRate(int $currentViewers, int $previousViewers): float
    {
        if ($previousViewers == 0) {
            return $currentViewers > 0 ? 100.0 : 0.0;
        }

        return round((($currentViewers - $previousViewers) / $previousViewers) * 100, 2);
    }

    private function getPreviousWeekAnalytics(Partner $partner, StreamProvider $provider, Carbon $date): ?StreamAnalytics
    {
        return StreamAnalytics::where('partner_id', $partner->id)
            ->where('provider', $provider)
            ->where('date', $date->copy()->subWeek())
            ->first();
    }

    private function calculateLongestStreak(Partner $partner, StreamProvider $provider, Carbon $date): int
    {
        // Look back 30 days to find longest consecutive streaming days
        $startDate = $date->copy()->subDays(30);

        $streamingDays = StreamSession::where('partner_id', $partner->id)
            ->where('provider', $provider)
            ->whereBetween('started_at', [$startDate, $date])
            ->selectRaw('DATE(started_at) as stream_date')
            ->distinct()
            ->orderBy('stream_date')
            ->pluck('stream_date')
            ->map(fn ($date) => Carbon::parse($date));

        return $this->findLongestConsecutiveStreak($streamingDays);
    }

    private function calculateDaysStreamedThisWeek(Partner $partner, StreamProvider $provider, Carbon $date): int
    {
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        return StreamSession::where('partner_id', $partner->id)
            ->where('provider', $provider)
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->selectRaw('DATE(started_at) as stream_date')
            ->distinct()
            ->count();
    }

    private function findLongestConsecutiveStreak(Collection $dates): int
    {
        if ($dates->isEmpty()) {
            return 0;
        }

        $longestStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < $dates->count(); $i++) {
            $prevDate = $dates[$i - 1];
            $currentDate = $dates[$i];

            if ($currentDate->diffInDays($prevDate) === 1) {
                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);
            } else {
                $currentStreak = 1;
            }
        }

        return $longestStreak;
    }

    public function generateWeeklyAnalytics(?Carbon $weekStart = null): array
    {
        $weekStart = $weekStart ?? now()->startOfWeek();
        $results = ['week' => $weekStart->toDateString(), 'days_processed' => 0];

        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayResults = $this->handle($date);
            $results['days_processed']++;
        }

        return $results;
    }
}
