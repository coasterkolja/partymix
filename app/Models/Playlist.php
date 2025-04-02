<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'snapshot_id',
        'name',
        'description',
        'image',
    ];

    public function jams() {
        return $this->belongsToMany(Jam::class);
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'playlist_song');
    }

    public function toLiteral() {
        return literal(id: $this->id, name: $this->name, image: $this->image);
    }
}
