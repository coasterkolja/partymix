<?php

namespace App\Livewire;

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

        $cooldowns = $this->jam->cooldowns->where('created_at', '>', now()->subMinutes($this->jam->cooldownMinutes()));
        
        if ($this->query) {
            $items = SpotifyService::api($this->jam)->search($this->query, 'track', ['limit' => 10])->tracks->items;

            $this->results = collect($items)->map(function ($item) use ($cooldowns) {
                if($cd = $cooldowns->find($item->id)) {
                    $cdPercent = $cd->pivot->created_at->diffInMinutes(now());
                }

                return literal(
                    id: $item->id,
                    name: $item->name,
                    artist: Arr::join(Arr::map($item->artists, function ($item) {
                        return $item->name;
                    }), ', '),
                    image: Arr::last($item->album->images)->url,
                    isOnCooldown: $cd ? true : false,
                    cooldown: $cd ? $cdPercent : 0
                );
            });
        }

        return view('livewire.search-modal');
    }

    public function addToQueue(string $id) {
        $this->dispatch('addToQueue', $id);
        Flux::modal('search-song')->close();
        // $this->query = '';
        // $this->results = collect();
    }
}
