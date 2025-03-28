<?php

namespace App\Livewire;

use App\Events\JamUpdated;
use App\Models\Jam;
use App\Models\Song;
use Livewire\Component;

class ViewJam extends Component
{
    public Jam $jam;

    public function mount(Jam $jam)
    {
        $this->jam = $jam;
    }

    public function getListeners() {
        return [
            'echo:jam.'.$this->jam->id.',JamUpdated' => 'updateJam',
            'addToQueue' => 'addToQueue',
        ];
    }

    public function updateJam() {
        $this->jam->refresh();
    }

    public function render()
    {
        return view('livewire.jam.view');
    }

    public function addToQueue(array $songData)
    {
        $song = Song::firstOrCreate($songData);
        $this->jam->queue()->attach($song->id);
        event(new JamUpdated($this->jam));
    }
}
