<?php

namespace App\Services;

use App\Models\User\Member;
use App\Models\User\User;

class MemberService
{
    public function createMember(User $user): void
    {
        Member::create([
            'memb___id' => $user->name,
            'memb__pwd' => $user->getRawPassword(),
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
        ]);
    }

    public function updateMember(User $user): void
    {
        $member = $user->member();
        $updates = [];

        if ($user->isDirty('email')) {
            $updates['mail_addr'] = $user->email;
        }

        if ($user->getRawPassword()) {
            $updates['memb__pwd'] = $user->getRawPassword();
        }

        if (! empty($updates)) {
            $member->update($updates);
        }
    }
}
