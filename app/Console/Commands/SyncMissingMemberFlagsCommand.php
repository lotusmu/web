<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMissingMemberFlagsCommand extends Command
{
    protected $signature = 'app:sync-missing-member-flags';

    protected $description = 'Set member_created flag for all verified users with existing members';

    public function handle(): int
    {
        $this->info('Starting to sync missing member flags...');

        // Get all verified users without member_created flag BUT with existing members
        $users = User::whereNotNull('email_verified_at')
            ->where('member_created', false)
            ->whereHas('member') // Only users who already have a member record
            ->get();

        $this->info("Found {$users->count()} verified users with members but without member_created flag");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $success = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                // Just update the flag
                $user->forceFill(['member_created' => true])->saveQuietly();
                $success++;
            } catch (Exception $e) {
                $this->error(" ✗ Failed to update flag for user {$user->name}: {$e->getMessage()}");
                Log::error('Failed to sync member flag', [
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

        $this->info("Completed: {$success} users updated successfully, {$errors} errors");

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
