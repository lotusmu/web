<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Actions\Logout;
use App\Livewire\BaseComponent;
use Illuminate\Support\Facades\Auth;

class VerifyEmail extends BaseComponent
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Flux::toast(
            text: __('A new verification link has been sent to the email address you provided during registration.'),
            heading: __('Success'),
            variant: 'success',
        );
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    protected function getViewName(): string
    {
        return 'pages.auth.verify-email';
    }

    protected function getLayoutType(): string
    {
        return 'auth';
    }
}
