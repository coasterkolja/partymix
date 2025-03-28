<?php

namespace App\Services;

use App\Models\Jam;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyService
{
    public static function api($jam)
    {
        if ($jam instanceof Jam) {
            if (now()->gte($jam->expiration_date)) {
                $session = new Session(
                    config('services.spotify.client_id'),
                    config('services.spotify.client_secret'),
                    config('services.spotify.redirect_uri')
                );
                
                $session->refreshAccessToken($jam->refresh_token);

                $jam->access_token = $session->getAccessToken();
                $jam->refresh_token = $session->getRefreshToken();
                $jam->expiration_date = now()->addSeconds(3600);

                $jam->save();

                $token = $jam->access_token;
            }
        } elseif (is_string($jam)) {
            $token = $jam;
        }

        $api = new SpotifyWebAPI();
        $api->setAccessToken($token);

        return $api;
    }
}
