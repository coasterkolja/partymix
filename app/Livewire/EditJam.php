<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Jam;
use Livewire\Component;
use App\Models\Playlist;
use App\Jobs\FetchPlaylist;
use Livewire\Attributes\Rule;
use App\Services\SpotifyService;
use Illuminate\Database\UniqueConstraintViolationException;

class EditJam extends Component
{
    #[Rule('required')]
    #[Rule('string')]
    public string $playlistUrl;

    public int $cooldown = 0;

    public Jam $jam;

    public function mount(Jam $jam)
    {
        $this->jam = $jam;
        $this->cooldown = $this->jam->song_cooldown;
    }

    public function addPlaylist()
    {
        $this->validate();

        try {
            $id = str(parse_url($this->playlistUrl)['path'])->explode('/')->last();
            $playlist = SpotifyService::api($this->jam)->getPlaylist($id);

            $playlistModel = Playlist::firstOrCreate(
                [
                    'id' => $playlist->id,
                ],
                [
                    'name' => $playlist->name,
                    'image' => $playlist->images[0]->url,
                    'snapshot_id' => $playlist->snapshot_id,
                ]
            );

            $this->jam->playlists()->attach($playlistModel->id);
            dispatch(new FetchPlaylist($this->jam, $playlistModel->id));

            unset($this->playlistUrl);
        } catch (\Throwable $e) {
            if ($e instanceof UniqueConstraintViolationException) {
                $this->addError('playlistUrl', 'Playlist already added: ' . $playlist->name);

                return;
            }

            $this->addError('playlistUrl', 'Invalid Playlist ID');
        }

        $this->jam->hadActionNow();
    }

    public function removePlaylist($id)
    {
        $this->jam->playlists()->detach($id);
        $this->jam->hadActionNow();
    }

    public function setCooldown()
    {
        $this->validate([
            'cooldown' => 'required|int',
        ]);

        $this->jam->song_cooldown = $this->cooldown;
        $this->jam->hadActionNow();
    }

    public function render()
    {
        return view('livewire.jam.edit');
    }
}

/*
https://open.spotify.com/playlist/6pwbyAThEHpCqCoYaeR6Lb : mein alkoholproblem
https://open.spotify.com/playlist/7IG1bXM4muyYn83lWwryOl : knockout von cali
https://open.spotify.com/playlist/4KVfB3a18EBoPHPjTlrucY : genussstängel
https://open.spotify.com/playlist/1Pwjt9bwOijnrFZI9fJcts : 80s80s
*/

// DB "AAAACw8VClwYa0KX/wTrElg6J2uFvejv"
// SF "AAAADAH10+tiMQ7OvHTRtrn2espFcxfQ"