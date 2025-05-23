<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\JamUpdated;
use App\Models\Jam;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CheckPlayback implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Jam $jam) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Delete all jobs for this jam
        DB::table('jobs')->where('payload', 'like', '%'.$this->jam->id.'%')->delete();

        // When Jam was inactive for too long, delete it
        if ($this->jam->isInactiveForTooLong()) {
            $this->jam->purge();
            return;
        }

        // Delete all cooldowns older than cooldown time
        $this->jam->overdueCooldowns()->detach();

        // Update the playback state in the database
        $this->jam->updatePlaystate();

        if ($this->jam->wasChanged('current_song_id')) {
            // Dispatch Event to reflect change on the client
            event(new JamUpdated($this->jam));

            // Add song to history
            $this->jam->addCurrentSongToHistory();
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
