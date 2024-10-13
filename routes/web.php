<?php

use App\Livewire\UpcomingEvents;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Volt::route('dashboard', 'dashboard.index')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('wallet', 'wallet.index')
    ->middleware(['auth', 'verified'])
    ->name('wallet');

Volt::route('entries', 'entries.index')
    ->middleware(['auth', 'verified'])
    ->name('entries');

Volt::route('activities', 'activities.index')
    ->middleware(['auth', 'verified'])
    ->name('activities');

Volt::route('support', 'support.index')
    ->middleware(['auth', 'verified'])
    ->name('support');

Volt::route('support/create-ticket', 'support.create-ticket')
    ->middleware(['auth', 'verified'])
    ->name('support.create-ticket');

Volt::route('support/ticket/{ticket}', 'support.show-ticket')
    ->middleware(['auth', 'verified'])
    ->name('support.show-ticket');

Volt::route('vip', 'vip.index')
    ->middleware(['auth', 'verified'])
    ->name('vip');

Volt::route('/profile', 'profile.index')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

require __DIR__.'/auth.php';
