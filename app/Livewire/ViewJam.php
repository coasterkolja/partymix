<?php

declare(strict_types=1);

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

    public function getListeners()
    {
        return [
            'echo:jam.'.$this->jam->id.',JamUpdated' => 'updateJam',
            'addToQueue' => 'addToQueue',
        ];
    }

    public function updateJam()
    {
        $this->jam->refresh();
    }

    public function render()
    {
        return view('livewire.jam.view');
    }

    public function skip()
    {
        Gate::allowIf($this->jam->host_token === session('token'));
        $this->jam->skip();
    }

    public function addToQueue(string $songId)
    {
        Song::fetchAndSave($songId, $this->jam);

        if ($this->jam->queue()->find($songId)) {
            return;
        }

        if ($this->jam->cooldowns()->find($songId)) {
            return;
        }
        
        $this->jam->queue()->attach($songId);

        $this->jam->hadActionNow();

        event(new JamUpdated($this->jam));
    }
}
