<?php

namespace App\Livewire;

use App\Events\JamUpdated;
use App\Models\Jam;
use App\Models\Song;
use Illuminate\Support\Facades\Gate;
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

    public function skip() {
        Gate::allowIf($this->jam->access_token === session('token'));
        $this->jam->skip();
    }

    public function addToQueue(array $songId)
    {
        Song::fetchAndSave($songId, $this->jam->access_token);
        $this->jam->queue()->attach($songId);
        event(new JamUpdated($this->jam));
    }
}
