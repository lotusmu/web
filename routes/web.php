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

Volt::route('/profile', 'profile.index')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

require __DIR__.'/auth.php';
