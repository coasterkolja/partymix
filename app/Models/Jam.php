<?php

namespace App\Models;

use App\Jobs\CheckPlayback;
use App\Services\SpotifyService;
use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected function casts()
    {
        return [
            'expiration_date' => 'datetime',
            'song_endtime' => 'datetime',
        ];
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function queue()
    {
        return $this->belongsToMany(Song::class, 'queued_songs', 'song_id', 'jam_id')
            ->withTimestamps()
            ->orderBy('queued_songs.created_at');
    }

    public function currentSong() {
        return $this->hasOne(Song::class, 'id', 'current_song_id');
    }

    public function cooldownTimeHuman()
    {
        return '60 Minuten';
    }

    public function remainingTime()
    {
        return $this->song_endtime ? now()->diffInSeconds($this->song_endtime) : 0;
    }

    public function updatePlaystate()
    {
        $playstate = SpotifyService::api($this->access_token)->getMyCurrentPlaybackInfo();

        $this->song_endtime = $playstate->item->duration_ms ? now()->addSeconds(($playstate->item->duration_ms - $playstate->progress_ms) / 1000) : null;
        $this->is_playing = $playstate->is_playing;
        $this->current_song_id = $playstate->item->id;

        $this->save();

        Song::fetchAndSave($this->current_song_id, $this->access_token);
    }

    public function queueNextTrack()
    {
        $song = $this->queue()->first();
        if ($song === null) return false;
        
        SpotifyService::api($this->access_token)->queue($song->id);
        $this->queue()->detach($song->id);
        
        return true;
    }

    public function skip() {
        $this->queueNextTrack();
        SpotifyService::api($this->access_token)->next();
        CheckPlayback::dispatch($this);
    }
}
