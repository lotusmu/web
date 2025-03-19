<?php

namespace App\Actions\Member;

use App\Models\Game\Wallet;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class SyncMember
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user) {
            $member = Member::firstOrNew(['memb___id' => $user->name]);

            $updates = $this->getUpdates($user, $member);

            if (! empty($updates)) {
                $member->fill($updates)->save();

                $this->createWalletIfNeeded($user, $member);
            }
        });
    }

    public function createWithPassword(User $user, string $password): void
    {
        DB::transaction(function () use ($user, $password) {
            $member = Member::firstOrNew(['memb___id' => $user->name]);

            if (! $member->exists) {
                $data = $this->getBaseMemberData($user);
                $data['memb__pwd'] = $password; // Use the provided password directly

                $member->fill($data)->save();

                $this->createWalletIfNeeded($user, $member);
            }
        });
    }

    private function createWalletIfNeeded(User $user, Member $member): void
    {
        if ($member->wasRecentlyCreated) {
            Wallet::create([
                'AccountID' => $user->name,
                'WCoinC' => 0,
                'zen' => 0,
            ]);
        }
    }

    private function getUpdates(User $user, Member $member): array
    {
        if (! $member->exists) {
            return $this->getNewMemberData($user);
        }

        return $this->getExistingMemberUpdates($user);
    }

    private function getBaseMemberData(User $user): array
    {
        return [
            'memb_name' => $user->name,
            'mail_addr' => $user->email,
            'sno__numb' => 1111111111111,
            'appl_days' => 0,
            'mail_chek' => 0,
            'bloc_code' => 0,
            'ctl1_code' => 0,
            'AccountLevel' => 0,
            'AccountExpireDate' => now(),
            'tokens' => 0,
        ];
    }

    private function getNewMemberData(User $user): array
    {
        $data = $this->getBaseMemberData($user);
        $data['memb__pwd'] = $user->getRawPassword();

        return $data;
    }

    private function getExistingMemberUpdates(User $user): array
    {
        $updates = [];

        if ($user->isDirty('email')) {
            $updates['mail_addr'] = $user->email;
        }

        if ($user->getRawPassword()) {
            $updates['memb__pwd'] = $user->getRawPassword();
        }

        return $updates;
    }
}
