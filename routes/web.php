<?php

declare(strict_types=1);

use App\Http\Middleware\Host;
use App\Livewire\CreateJam;
use App\Livewire\EditJam;
use App\Livewire\ViewJam;
use App\Livewire\Welcome;
use App\Livewire\Queue;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', Welcome::class)->name('welcome');
Route::get('auth', function () {
    return Socialite::driver('spotify')
        ->setScopes(['user-read-playback-state', 'user-modify-playback-state'])
        ->redirect();
})->name('jams.auth');

Route::get('create', CreateJam::class)->name('jams.create');
Route::get('jam/{jam}', ViewJam::class)->name('jams');
Route::get('jam/{jam}/edit', EditJam::class)->middleware(Host::class)->name('jams.edit');
Route::get('jam/{jam}/queue', Queue::class)->middleware(Host::class)->name('jams.queue');