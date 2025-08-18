<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\BaseComponent;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends BaseComponent
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        Flux::toast(__($status));
    }

    protected function getViewName(): string
    {
        return 'pages.auth.forgot-password';
    }

    protected function getLayoutType(): string
    {
        return 'auth';
    }
}
