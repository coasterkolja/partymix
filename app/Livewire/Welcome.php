<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Welcome')]
class Welcome extends Component
{
    #[Rule('required', message: 'Jam ID erforderlich')]
    #[Rule('exists:jams,id', message: 'Jam nicht gefunden')]
    public string $jamId;

    public function join()
    {
        $this->validate();

        $this->redirect(route('jams', $this->jamId), navigate: true);
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
