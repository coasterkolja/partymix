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
        $song = SpotifyService::api($jam)->getTrack($id);

        self::firstOrCreate([
            'id' => $song->id,
        ], [
            'name' => $song->name,
            'artist' => Arr::join(Arr::map($song->artists, function ($item) {
                return $item->name;
            }), ', '),
            'image' => Arr::last($song->album->images)->url,
        ]);
    }

    public function uri(): string
    {
        return 'spotify:track:'.$this->id;
    }
}
