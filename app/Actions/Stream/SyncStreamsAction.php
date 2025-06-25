<?php

namespace App\Actions\Stream;

use App\Services\Stream\TwitchService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncStreamsAction
{
    public function handle(): array
    {
        $results = [
            'total_results' => [],
            'errors' => [],
            'success' => false,
        ];

        try {
            // Sync Twitch streams
            $twitchService = new TwitchService;
            $twitchResults = $twitchService->syncPartnerStreams();

            $results['total_results']['twitch'] = $twitchResults;
            $results['success'] = $twitchResults['errors'] === 0;

            // Generate analytics for today if we found new data
            if ($twitchResults['sessions_created'] > 0 || $twitchResults['sessions_ended'] > 0) {
                $analyticsAction = new GenerateStreamAnalytics;
                $analyticsResults = $analyticsAction->handle();
                $results['total_results']['analytics'] = $analyticsResults;
            }

            Cache::forget('active-streams-public');

            Log::info('Manual stream sync completed', $results);

        } catch (Exception $e) {
            $results['errors'][] = $e->getMessage();
            $results['success'] = false;

            Log::error('Manual stream sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $results;
    }

    public function getFormattedResults(array $results): string
    {
        if (! $results['success']) {
            return 'Sync failed: '.implode(', ', $results['errors']);
        }

        $twitchResults = $results['total_results']['twitch'] ?? [];

        $message = sprintf(
            'Sync completed! Checked %d partners, found %d live streams, created %d sessions, ended %d sessions.',
            $twitchResults['checked'] ?? 0,
            $twitchResults['live_found'] ?? 0,
            $twitchResults['sessions_created'] ?? 0,
            $twitchResults['sessions_ended'] ?? 0
        );

        if (isset($results['total_results']['analytics'])) {
            $analytics = $results['total_results']['analytics'];
            $message .= sprintf(
                ' Analytics: %d partners processed, %d created, %d updated.',
                $analytics['partners_processed'] ?? 0,
                $analytics['analytics_created'] ?? 0,
                $analytics['analytics_updated'] ?? 0
            );
        }

        return $message;
    }
}
