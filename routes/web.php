<?php

use App\Http\Middleware\CheckArticlePublishedMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes

Volt::route('/', 'pages.guest.home.index')
    ->name('guest.home');

Volt::route('/files', 'pages.guest.files.index')
    ->name('files');

Volt::route('/schedule', 'pages.guest.schedule.index')
    ->name('schedule');

Volt::route('/terms', 'pages.guest.terms.index')
    ->name('terms');

Volt::route('/terms', 'pages.guest.legal.terms')
    ->name('terms');

Volt::route('/privacy', 'pages.guest.legal.privacy')
    ->name('privacy');

Volt::route('/refund', 'pages.guest.legal.refund')
    ->name('refund');

Volt::route('/guidelines', 'pages.guest.legal.guidelines')
    ->name('guidelines');

Route::prefix('articles')->group(function () {
    Volt::route('/', 'pages.guest.articles.index')
        ->name('articles');

    Volt::route('/{article:slug}', 'pages.guest.articles.show')
        ->middleware(CheckArticlePublishedMiddleware::class)
        ->name('articles.show');
});

Route::prefix('server')->group(function () {
    Volt::route('/overview', 'pages.guest.server.overview')
        ->name('server.overview');
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
