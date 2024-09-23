<?php

use App\Livewire\UpcomingEvents;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/upcoming-events', UpcomingEvents::class)->name('upcoming-events');

require __DIR__.'/auth.php';
