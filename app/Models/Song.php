<?php

namespace App\Models;

use Illuminate\Support\Arr;
use App\Services\SpotifyService;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    public $keyType = "string";

    public static function fetchAndSave($id, $token) {
        $song = SpotifyService::api($token)->getTrack($id);
        
        self::firstOrCreate([
            'id' => $song->id,
            'name' => $song->name,
            'artist' => Arr::join(Arr::map($song->artists, function ($item) {
                return $item->name;
            }), ', '),
            'image' => Arr::last($song->album->images)->url,
        ]);
    }
}
