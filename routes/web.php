<?php

use App\Livewire\EditJam;
use App\Livewire\ViewJam;
use App\Livewire\Welcome;
use App\Livewire\CreateJam;
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
Route::get('jam/{jam}/edit', EditJam::class)->name('jams.edit');
