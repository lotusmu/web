<?php

namespace App\Actions\Member;

use App\Models\Game\DailyReward;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class BackfillDailyRewards
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $accountId
    ) {}

    public function handle(): void
    {
        try {
            $now = Carbon::now();

            if ($now->day === 1) {
                Log::info('Skipping backfill for first day of month', [
                    'account_id' => $this->accountId,
                ]);

                return;
            }

            $previousDay = $now->day - 1;
            $currentMonth = $now->month;

            $existingRewards = DailyReward::where('AccountID', $this->accountId)
                ->where('Month', $currentMonth)
                ->pluck('Day');

            $daysToFill = range(1, $previousDay);
            $missingDays = array_diff($daysToFill, $existingRewards->toArray());

            if (empty($missingDays)) {
                Log::info('No missing days to backfill', [
                    'account_id' => $this->accountId,
                    'month' => $currentMonth,
                ]);

                return;
            }

            foreach ($missingDays as $day) {
                DailyReward::create([
                    'AccountID' => $this->accountId,
                    'Day' => $day,
                    'Month' => $currentMonth,
                ]);
            }

            activity('daily_rewards')
                ->withProperties([
                    'account_id' => $this->accountId,
                    'month' => $currentMonth,
                    'days_count' => count($missingDays),
                ])
                ->log('Backfilled daily rewards for :properties.days_count days');

        } catch (Exception $e) {
            Log::error('Failed to backfill daily rewards', [
                'account_id' => $this->accountId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
