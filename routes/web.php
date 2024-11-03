<?php

use App\Livewire\UpcomingEvents;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes
Route::view('/', 'welcome');
Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

//Profile route
Volt::route('/profile', 'profile.index')
    ->middleware(['auth'])
    ->name('profile');

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Volt::route('dashboard', 'dashboard.index')
        ->name('dashboard');

    // Wallet
    Volt::route('wallet', 'wallet.index')
        ->name('wallet');

    // Entries
    Volt::route('entries', 'entries.index')
        ->name('entries');

    // Activities
    Volt::route('activities', 'activities.index')
        ->name('activities');

    // Support routes group
    Route::prefix('support')->group(function () {
        Volt::route('/', 'support.index')
            ->name('support');
        Volt::route('/create-ticket', 'support.create-ticket')
            ->name('support.create-ticket');
        Volt::route('/ticket/{ticket}', 'support.show-ticket')
            ->name('support.show-ticket');
    });

    // VIP routes group
    Route::prefix('vip')->group(function () {
        Volt::route('/', 'vip.index')
            ->name('vip');
        Volt::route('/purchase', 'vip.purchase')
            ->name('vip.purchase');
    });
});
