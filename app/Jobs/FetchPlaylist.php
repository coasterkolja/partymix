<?php

namespace App\Jobs;

use App\Models\Jam;
use App\Models\Playlist;
use App\Models\Song;
use App\Services\SpotifyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchPlaylist implements ShouldQueue
{
    use Queueable;

    private int $trackLimit = 50;

    /**
     * Create a new job instance.
     */
    public function __construct(public Jam $jam, public string $id) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tracks = [];

        $metadata = SpotifyService::api($this->jam)->getPlaylist($this->id, [
            'fields' => 'tracks(total),snapshot_id',
        ]);

        $total = $metadata->tracks->total;
        $snapshotId = $metadata->snapshot_id;

        if (($playlist = Playlist::find($this->id))->snapshot_id !== $snapshotId) {
            $playlist->songs()->detach();
            $playlist->update(['snapshot_id' => $snapshotId]);
        }

        for ($i = 0; $i < $total; $i += $this->trackLimit) {
            $tracks = array_merge($tracks, SpotifyService::api($this->jam)->getPlaylistTracks($this->id, [
                'limit' => $this->trackLimit,
                'offset' => $i,
                'fields' => 'items(track(id,name,artists(name),album(images)))',
            ])->items);
        }

        $trackIds = [];

        foreach ($tracks as $track) {
            $trackIds[] = Song::createFromPlaylist($track->track)->id;
        }

        $playlist->songs()->attach($trackIds);
    }
}
