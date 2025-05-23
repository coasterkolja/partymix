<?php

namespace App\Console\Commands;

use App\Models\Jam;
use Illuminate\Console\Command;

class PurgeAllJams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jams:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all jams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jams = Jam::all();

        foreach ($jams as $jam) {
            $jam->purge();
            $this->info('Deleted ' . $jam->id);
        }

        $this->info('Done!');
    }
}
