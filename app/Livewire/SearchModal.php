<?php

namespace App\Livewire;

use App\Events\JamUpdated;
use Flux\Flux;
use App\Models\Jam;
use App\Support\Arr;
use Livewire\Component;
use App\Services\SpotifyService;
use Illuminate\Support\Collection;

class SearchModal extends Component
{
    public string $query = '';
    public Collection $results;
    public Jam $jam;

    public function mount(Jam $jam)
    {
        $this->jam = $jam;
        $this->results = collect();
    }

    public function render()
    {
        if ($this->results->isNotEmpty() && !$this->query) {
            $this->results = collect();
        }
        
        if ($this->query) {
            $items = SpotifyService::api($this->jam->access_token)->search($this->query, 'track', ['limit' => 10])->tracks->items;

            $this->results = collect($items)->map(function ($item) {
                return literal(
                    id: $item->id,
                    name: $item->name,
                    artist: Arr::join(Arr::map($item->artists, function ($item) {
                        return $item->name;
                    }), ', '),
                    image: Arr::last($item->album->images)->url,
                );
            });
        }

        return view('livewire.search-modal');
    }

    public function addToQueue(string $id) {
        $song = $this->results->firstWhere('id', $id);
        $this->dispatch('addToQueue', $song);
        Flux::modal('search-song')->close();
        // $this->query = '';
        // $this->results = collect();
    }
}
