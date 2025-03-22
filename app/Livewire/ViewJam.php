<?php

namespace App\Livewire;

use App\Models\Jam;
use Livewire\Component;

class ViewJam extends Component
{
    public Jam $jam;

    public function mount(Jam $jam)
    {
        $this->jam = $jam;
    }

    public function render()
    {
        return view('livewire.jam.view');
    }
}
