<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function jams() {
        return $this->belongsToMany(Jam::class);
    }

    public function toLiteral() {
        return literal(id: $this->id, name: $this->name, image: $this->image);
    }
}
