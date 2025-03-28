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
        $this->jam->updatePlaystate();

        if ($this->jam->wasChanged('current_song_id')) {
            event(new JamUpdated($this->jam));
        }

        if (! $this->jam->is_playing) {
            self::dispatch($this->jam)
                ->delay(now()->addSeconds(5));
            return;
        }

        if ($this->jam->remainingTime() > 10) {
            self::dispatch($this->jam)
                ->delay(now()->addSeconds($this->jam->remainingTime() - 5));
            return;
        }

        $this->jam->queueNextTrack();
        self::dispatch($this->jam)->delay(now()->addSeconds(10));
        event(new JamUpdated($this->jam));
    }
}
