<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Jam;
use Livewire\Component;
use App\Jobs\CheckPlayback;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class CreateJam extends Component
{
    public string $jamId;

    public $user;

    public function mount()
    {
        try {
            $spotifyUser = Socialite::driver('spotify')->user();

            $this->jamId = $this->generateJamId();
            $this->user = literal(token: $spotifyUser->token, refreshToken: $spotifyUser->refreshToken, expirationDate: now()->addSeconds($spotifyUser->expiresIn));
        } catch (InvalidStateException $e) {
            Session::flash('spotify_error', 'An unexpected error occurred. Please try again.');
            $this->redirect(route('welcome'), navigate: true);
        }
    }

    public function create()
    {
        $hostToken = uniqid();

        $jam = Jam::create([
            'id' => $this->jamId,
            'access_token' => $this->user->token,
            'refresh_token' => $this->user->refreshToken,
            'expiration_date' => $this->user->expirationDate,
            'host_token' => $hostToken,
        ]);

        session()->put('token', $hostToken);

        CheckPlayback::dispatch($jam)/* ->delay(now()->addSeconds(5)) */;

        $this->redirect(route('jams.edit', $this->jamId), navigate: true);
    }

    public function render()
    {
        return view('livewire.jam.create');
    }

    private function generateJamId()
    {
        return mt_rand(1000, 9999).'-'.mt_rand(1000, 9999);
    }
}
