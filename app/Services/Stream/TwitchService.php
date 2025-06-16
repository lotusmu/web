<?php

namespace App\Services\Stream;

use App\Enums\Stream\StreamProvider;
use App\Models\Partner\Partner;
use App\Models\Stream\StreamSession;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwitchService
{
    private string $clientId;

    private string $clientSecret;

    private ?string $accessToken = null;

    public function __construct()
    {
        $this->clientId = config('services.twitch.client_id');
        $this->clientSecret = config('services.twitch.client_secret');
    }

    public function syncPartnerStreams(): array
    {
        $results = [
            'checked' => 0,
            'live_found' => 0,
            'sessions_created' => 0,
            'sessions_ended' => 0,
            'errors' => 0,
        ];

        // Get all partners with Twitch channels
        $twitchPartners = Partner::where('status', 'active')
            ->whereJsonContains('channels', ['platform' => 'twitch'])
            ->get();

        $results['checked'] = $twitchPartners->count();

        if ($twitchPartners->isEmpty()) {
            return $results;
        }

        try {
            // Get access token
            if (! $this->getAccessToken()) {
                $results['errors']++;

                return $results;
            }

            // Extract Twitch usernames
            $twitchUsernames = [];
            foreach ($twitchPartners as $partner) {
                $twitchChannel = collect($partner->channels)
                    ->firstWhere('platform', 'twitch');

                if ($twitchChannel) {
                    $twitchUsernames[] = $twitchChannel['name'];
                }
            }

            // Fetch live streams from Twitch API
            $liveStreams = $this->getLiveStreams($twitchUsernames);
            $results['live_found'] = count($liveStreams);

            // Process each partner
            foreach ($twitchPartners as $partner) {
                try {
                    $this->processPartnerStream($partner, $liveStreams, $results);
                } catch (Exception $e) {
                    $results['errors']++;
                    Log::error('Error processing partner stream', [
                        'partner_id' => $partner->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

        } catch (Exception $e) {
            $results['errors']++;
            Log::error('Twitch sync error', ['error' => $e->getMessage()]);
        }

        return $results;
    }

    private function getAccessToken(): bool
    {
        try {
            $response = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];

                return true;
            }

            Log::error('Failed to get Twitch access token', ['response' => $response->body()]);

            return false;

        } catch (Exception $e) {
            Log::error('Twitch auth error', ['error' => $e->getMessage()]);

            return false;
        }
    }

    private function getLiveStreams(array $usernames): array
    {
        if (empty($usernames) || ! $this->accessToken) {
            return [];
        }

        try {
            // Twitch API allows max 100 usernames per request
            $chunks = array_chunk($usernames, 100);
            $allStreams = [];

            foreach ($chunks as $chunk) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->accessToken,
                    'Client-Id' => $this->clientId,
                ])->get('https://api.twitch.tv/helix/streams', [
                    'user_login' => $chunk,
                ]);

                if ($response->successful()) {
                    $streams = $response->json()['data'] ?? [];
                    $allStreams = array_merge($allStreams, $streams);
                } else {
                    Log::warning('Failed to fetch Twitch streams', [
                        'usernames' => $chunk,
                        'response' => $response->body(),
                    ]);
                }
            }

            return $allStreams;

        } catch (Exception $e) {
            Log::error('Error fetching Twitch streams', ['error' => $e->getMessage()]);

            return [];
        }
    }

    private function processPartnerStream(Partner $partner, array $liveStreams, array &$results): void
    {
        $twitchChannel = collect($partner->channels)
            ->firstWhere('platform', 'twitch');

        if (! $twitchChannel) {
            return;
        }

        $channelName = $twitchChannel['name'];

        // Find if this partner is currently live
        $liveStream = collect($liveStreams)
            ->firstWhere('user_login', strtolower($channelName));

        // Check for existing live session
        $existingSession = StreamSession::where('partner_id', $partner->id)
            ->where('provider', StreamProvider::TWITCH)
            ->whereNull('ended_at')
            ->first();

        if ($liveStream) {
            // Partner is live
            if (! $existingSession) {
                // Create new session
                $this->createStreamSession($partner, $liveStream, $channelName);
                $results['sessions_created']++;
            } else {
                // Update existing session with current data
                $this->updateStreamSession($existingSession, $liveStream);
            }
        } else {
            // Partner is not live
            if ($existingSession) {
                // End the session
                $existingSession->update([
                    'ended_at' => now(),
                ]);
                $results['sessions_ended']++;
            }
        }
    }

    private function createStreamSession(Partner $partner, array $streamData, string $channelName): void
    {
        $startedAt = now()->parse($streamData['started_at']);

        StreamSession::create([
            'partner_id' => $partner->id,
            'provider' => StreamProvider::TWITCH,
            'external_stream_id' => $streamData['id'],
            'channel_name' => $channelName,
            'title' => $streamData['title'] ?? '',
            'game_category' => $streamData['game_name'] ?? '',
            'language' => $streamData['language'] ?? 'en',
            'stream_tags' => $streamData['tags'] ?? [],
            'mature_content' => $streamData['is_mature'] ?? false,
            'started_at' => $startedAt,
            'peak_viewers' => $streamData['viewer_count'] ?? 0,
            'average_viewers' => $streamData['viewer_count'] ?? 0,
            'day_of_week' => $startedAt->dayOfWeek,
            'hour_of_day' => $startedAt->hour,
        ]);
    }

    private function updateStreamSession(StreamSession $session, array $streamData): void
    {
        $currentViewers = $streamData['viewer_count'] ?? 0;

        $session->update([
            'title' => $streamData['title'] ?? $session->title,
            'game_category' => $streamData['game_name'] ?? $session->game_category,
            'peak_viewers' => max($session->peak_viewers, $currentViewers),
            'average_viewers' => $this->calculateRunningAverage(
                $session->average_viewers,
                $currentViewers,
                $session->created_at
            ),
        ]);
    }

    private function calculateRunningAverage(int $currentAverage, int $newValue, $startTime): int
    {
        $minutesElapsed = now()->diffInMinutes($startTime);
        if ($minutesElapsed <= 1) {
            return $newValue;
        }

        // Simple running average calculation
        return round(($currentAverage * ($minutesElapsed - 1) + $newValue) / $minutesElapsed);
    }
}
