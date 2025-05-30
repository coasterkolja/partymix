<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Jam;

class JamObserver
{
    /**
     * Handle the Jam "created" event.
     */
    public function created(Jam $jam): void
    {
        $jam->generateQrCode();
        $jam->hadActionNow();
    }

    /**
     * Handle the Jam "updated" event.
     */
    public function updated(Jam $jam): void
    {
        //
    }

    /**
     * Handle the Jam "deleted" event.
     */
    public function deleted(Jam $jam): void
    {
        //
    }

    /**
     * Handle the Jam "restored" event.
     */
    public function restored(Jam $jam): void
    {
        //
    }

    /**
     * Handle the Jam "force deleted" event.
     */
    public function forceDeleted(Jam $jam): void
    {
        //
    }
}
