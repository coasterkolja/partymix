<?php

namespace App\Jobs;

use App\Models\Jam;
use App\Events\JamUpdated;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckPlayback implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Jam $jam)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Update the playback state in the database
        $this->jam->updatePlaystate();

        if ($this->jam->wasChanged('current_song_id')) {
            event(new JamUpdated($this->jam));
        }

        // When playback is paused check every 5 seconds if it started again
        if (! $this->jam->is_playing) {
            self::dispatch($this->jam)
                ->delay(now()->addSeconds(5));
            return;
        }

        // If playback is still active, check if we need to add a new song
        // If the remaining time is less than 10 seconds, add a new song
        if ($this->jam->remainingTime() > 10) {
            self::dispatch($this->jam)
                ->delay(now()->addSeconds($this->jam->remainingTime() - 5));
            return;
        }

        // If the remaining time is less than 10 seconds, add a new song
        $addedNewSong = $this->jam->queueNextTrack();
        // if ($addedNewSong) event(new JamUpdated($this->jam));
        
        self::dispatch($this->jam)->delay(now()->addSeconds(10));
    }
}
