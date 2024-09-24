<?php

use App\Livewire\UpcomingEvents;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('/profile', 'profile.index')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

require __DIR__.'/auth.php';
