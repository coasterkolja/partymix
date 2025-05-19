<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Jam;
use Livewire\Component;

class Queue extends Component
{
    public Jam $jam;

    public function mount(Jam $jam)
    {
        $this->jam = $jam;
    }

    public function remove(string $songId)
    {
        $this->jam->queue()->detach($songId);
        $this->jam->hadActionNow();
    }

    public function render()
    {
        return view('livewire.jam.queue');
    }
}
