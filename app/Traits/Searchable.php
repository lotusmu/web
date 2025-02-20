<?php

namespace App\Traits;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

trait Searchable
{
    private const MAX_ATTEMPTS = 30;

    private const DECAY_SECONDS = 60;

    public string $search = '';

    private function throttleKey(): string
    {
        return 'search:'.(Auth::user()?->id ?? request()->ip());
    }

    private function ensureIsNotRateLimited(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        Flux::toast(
            text: __('Too many search attempts. Please wait :seconds seconds.', ['seconds' => $seconds]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    protected function searchCharacter($query)
    {
        if ($this->search === '') {
            return $query;
        }

        if (! $this->ensureIsNotRateLimited()) {
            return $query;
        }

        RateLimiter::hit($this->throttleKey());

        return $query->where(function ($query) {
            $query->where('Name', 'like', $this->search.'%')
                ->orWhereHas('guildMember.guild', function ($query) {
                    $query->where('G_Name', 'like', $this->search.'%');
                });
        });
    }

    protected function searchGuild($query)
    {
        if ($this->search === '') {
            return $query;
        }

        if (! $this->ensureIsNotRateLimited()) {
            return $query;
        }

        RateLimiter::hit($this->throttleKey());

        return $query->where('G_Name', 'like', $this->search.'%');
    }

    public function updatedSearchable($property): void
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }
}
