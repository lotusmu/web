<?php

namespace App\Actions\Member;

use App\Models\User\TemporaryPassword;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

readonly class CreateMemberOnEmailVerification
{
    public function __construct(
        private SyncMember $syncMember
    ) {}

    public function handle(User $user): void
    {
        // Skip if member already created
        if ($user->member_created) {
            return;
        }

        try {
            // Get temporary password if exists
            $tempPassword = TemporaryPassword::where('user_id', $user->id)->first();

            if ($tempPassword) {
                // Create member with stored password
                $this->syncMember->createWithPassword($user, $tempPassword->password);

                // Clean up
                $tempPassword->delete();
            } else {
                // Create with new random password
                $this->createWithRandomPassword($user);
            }

            // Mark user as having member account
            $user->forceFill(['member_created' => true])->saveQuietly();

        } catch (Exception $e) {
            Log::error('Failed to create member on email verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw to allow controller to handle it
        }
    }

    private function createWithRandomPassword(User $user): void
    {
        $newPassword = Str::random(10);

        // Update user password
        $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->saveQuietly();

        // Create member with the password
        $this->syncMember->createWithPassword($user, $newPassword);

        // Note: You might want to notify the user about this password reset
        // $user->notify(new PasswordResetNotification($newPassword));
    }
}
