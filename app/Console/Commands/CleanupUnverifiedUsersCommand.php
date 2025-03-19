<?php

namespace App\Console\Commands;

use App\Models\User\Member;
use App\Models\User\TemporaryPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupUnverifiedUsersCommand extends Command
{
    protected $signature = 'app:cleanup-unverified-users {--days=2 : Number of days after which unverified users will be deleted} {--dry-run : Run without making changes} {--force : Skip confirmation prompt}';

    protected $description = 'Delete unverified users and their members after specified days';

    public function handle(): int
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info($dryRun ? 'DRY RUN MODE - No changes will be made' : 'LIVE MODE - Changes will be applied');
        $this->info("Finding unverified users older than {$days} days (created before {$cutoffDate})...");

        // Get unverified users older than specified days
        $users = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        $this->info("Found {$users->count()} unverified users to process");

        if ($users->isEmpty()) {
            $this->info('No users to delete. Exiting.');

            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'Username', 'Email', 'Created', 'Has Member'],
                $users->map(fn ($user) => [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i'),
                    $user->member()->exists() ? 'Yes' : 'No',
                ])
            );
            $this->info('Dry run completed. No records were deleted.');

            return Command::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Are you sure you want to permanently delete these {$users->count()} users and their associated data?")) {
            $this->info('Operation cancelled.');

            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $deleted = 0;
        $errors = 0;
        $skipped = 0;

        foreach ($users as $user) {
            DB::beginTransaction();
            try {
                // Check for member and delete if exists
                $member = Member::where('memb___id', $user->name)->first();

                if ($member) {
                    // Check if member is safe to delete (add any business logic here)
                    // For example, maybe don't delete members with active subscriptions
                    // or members with characters above a certain level

                    // For this example, we'll just delete all unverified members
                    $member->delete();
                    $this->line(" ✓ Deleted member: {$user->name}");
                }

                // Delete temporary password if exists
                TemporaryPassword::where('user_id', $user->id)->delete();

                // Delete user
                $user->delete();

                DB::commit();
                $deleted++;

            } catch (Exception $e) {
                DB::rollBack();
                $this->error(" ✗ Failed to delete user {$user->name}: {$e->getMessage()}");
                Log::error('Failed to delete unverified user', [
                    'user_id' => $user->id,
                    'username' => $user->name,
                    'error' => $e->getMessage(),
                ]);
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Cleanup completed: {$deleted} users deleted, {$errors} errors");

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
