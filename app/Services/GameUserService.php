<?php

namespace App\Services;

use App\Models\Game\User as GameUser;
use App\Models\User;

class GameUserService
{
    public function createGameUser(User $user): void
    {
        GameUser::create([
            'memb___id' => $user->username,
            'memb__pwd' => $user->getRawPassword(),
            'memb_name' => $user->username,
            'mail_addr' => $user->email,
            'sno__numb' => 1111111111111,
            'appl_days' => 0,
            'mail_chek' => 0,
            'bloc_code' => 0,
            'ctl1_code' => 0,
            'AccountLevel' => 0,
            'AccountExpireDate' => now(),
        ]);
    }

    public function updateGameUser(User $user): void
    {
        $gameUser = $user->gameUser;
        if ($gameUser) {
            $updates = [];

            if ($user->isDirty('email')) {
                $updates['mail_addr'] = $user->email;
            }

            if ($user->getRawPassword()) {
                $updates['memb__pwd'] = $user->getRawPassword();
            }

            if (! empty($updates)) {
                $gameUser->update($updates);
            }
        }
    }
}
