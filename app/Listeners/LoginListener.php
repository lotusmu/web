<?php

namespace App\Listeners;

use App\Services\LoginActivityService;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    protected LoginActivityService $loginActivityService;

    public function __construct(LoginActivityService $loginActivityService)
    {
        $this->loginActivityService = $loginActivityService;

    }

    public function handle(Login $event): void
    {
        $this->loginActivityService->record(request(), $event->user);
    }
}
