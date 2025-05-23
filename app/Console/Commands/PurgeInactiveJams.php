<?php

namespace App\Console\Commands;

use App\Models\Jam;
use Illuminate\Console\Command;

class PurgeInactiveJams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jams:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes inactive jams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jams = Jam::where('last_action_at', '<', now()->subMinutes(60))->get();

        foreach ($jams as $jam) {
            $jam->purge();
            $this->info('Deleted ' . $jam->id);
        }
        
        $this->info('Done!');
    }
}
