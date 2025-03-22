<?php

namespace App\Services;

use App\Models\Jam;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyService {
    public static function api($token) {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($token);

        return $api;
    }
}