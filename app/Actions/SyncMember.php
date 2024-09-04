<?php

namespace App\Actions;

use App\Models\User\Member;
use App\Models\User\User;

class SyncMember
{
    public function handle(User $user): void
    {
        Member::updateOrCreate(
            ['memb___id' => $user->name],
            [
                'memb__pwd' => $user->getRawPassword() ?? $user->member->memb__pwd,
                'memb_name' => $user->name,
                'mail_addr' => $user->email,
                'sno__numb' => $user->member->sno__numb ?? 1111111111111,
                'appl_days' => $user->member->appl_days ?? 0,
                'mail_chek' => $user->member->mail_chek ?? 0,
                'bloc_code' => $user->member->bloc_code ?? 0,
                'ctl1_code' => $user->member->ctl1_code ?? 0,
                'AccountLevel' => $user->member->AccountLevel ?? 0,
                'AccountExpireDate' => $user->member->AccountExpireDate ?? now(),
                'tokens' => $user->member->tokens ?? 0,
            ]
        );
    }
}
