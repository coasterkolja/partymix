<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\SpotifyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Song extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $isOnCooldown = false;

    protected $cooldownPercent = 0;

    protected $fillable = [
        'id',
        'name',
        'artist',
        'image',
    ];

    public static function fetchAndSave($id, $jam)
    {
        $track = SpotifyService::api($jam)->getTrack($id);

        self::updateOrCreate([
            'id' => $track->id,
        ], [
            'name' => $track->name,
            'artist' => self::createArtistString($track->artists),
            'image' => self::lastImageUrl($track->album->images),
        ]);
    }

    public static function createFromPlaylist($track) {
        return self::updateOrCreate([
            'id' => $track->id,
        ], [
            'name' => $track->name,
            'artist' => self::createArtistString($track->artists),
            'image' => self::lastImageUrl($track->album->images),
        ]);
    }

    public static function createArtistString($artists) {
        return Arr::join(Arr::map($artists, function ($item) {
            return $item->name;
        }), ', ');
    }

    public static function lastImageUrl($images) {
        return Arr::last($images)->url;
    }

    public function uri(): string
    {
        return 'spotify:track:'.$this->id;
    }
}
