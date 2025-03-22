<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Laravel\Socialite\Facades\Socialite;

#[Title('Welcome')]
class Welcome extends Component
{
    #[Rule('required')]
    #[Rule('exists:jams,id')]
    public string $jamId;

    public function create()
    {
        return redirect()->route('jams.auth');
    }

    public function join()
    {
        $this->validate();

        return null;
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
