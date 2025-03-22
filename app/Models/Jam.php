<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public const PLAYSTATES = [
        'playing',
        'paused',
        'stopped',
    ];

    protected function casts()
    {
        return [
            'expiration_date' => 'datetime',
        ];
    }

    public function playlists() {
        return $this->belongsToMany(Playlist::class);
    }

    public function queue() {
        return $this->belongsToMany(QueuedSong::class, 'queued_songs');
    }
}
