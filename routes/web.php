<?php

use App\Http\Middleware\CheckArticlePublishedMiddleware;
use App\Livewire\UpcomingEvents;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes
Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

Volt::route('/', 'pages.guest.home.index')->name('guest.home');

Route::prefix('news')->group(function () {
    Volt::route('/', 'pages.guest.news.index')
        ->name('news');

    Volt::route('/{article:slug}', 'pages.guest.news.show')
        ->middleware(CheckArticlePublishedMiddleware::class)
        ->name('news.show');
});

// Profile route
Volt::route('/profile', 'pages.profile.index')
    ->middleware(['auth'])
    ->name('profile');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Volt::route('dashboard', 'pages.dashboard.index')
        ->name('dashboard');

    // Wallet
    Volt::route('wallet', 'pages.wallet.index')
        ->name('wallet');

    // Entries
    Volt::route('event-entries', 'pages.entries.index')
        ->name('entries');

    // Castle Siege group
    Route::prefix('castle-siege')->group(function () {
        Volt::route('/', 'pages.castle.index')
            ->name('castle');
    });

    // VIP routes group
    Route::prefix('vip')->group(function () {
        Volt::route('/', 'pages.vip.index')
            ->name('vip');
        Volt::route('/purchase', 'pages.vip.purchase')
            ->name('vip.purchase');
    });

    // Stealth Mode
    Volt::route('stealth', 'pages.stealth.index')
        ->name('stealth');

    // Donate
    Volt::route('donate', 'pages.donate.index')
        ->name('donate');

    // Activities
    Volt::route('activities', 'pages.activities.index')
        ->name('activities');

    // Support routes group
    Route::prefix('support')->group(function () {
        Volt::route('/', 'pages.support.index')
            ->name('support');
        Volt::route('/create-ticket', 'pages.support.create-ticket')
            ->name('support.create-ticket');
        Volt::route('/ticket/{ticket}', 'pages.support.show-ticket')
            ->name('support.show-ticket');
    });
});

// Authentication routes
require __DIR__.'/auth.php';

// Payment routes
require __DIR__.'/payment.php';
