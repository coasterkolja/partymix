<?php

declare(strict_types=1);

namespace App\Models;

use App\Jobs\CheckPlayback;
use App\Observers\JamObserver;
use App\Services\SpotifyService;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[ObservedBy(JamObserver::class)]
class Jam extends Model
{
    protected $with = [
        'queue',
        'currentSong',
        'cooldowns',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'access_token',
        'refresh_token',
        'expiration_date',
        'current_song_id',
        'is_playing',
        'song_endtime',
        'last_action_at',
        'host_token',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
        'is_playing' => 'boolean',
        'song_endtime' => 'datetime',
        'last_action_at' => 'datetime',
    ];

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'jam_playlist');
    }

    public function queue(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'queued_songs')
            ->withTimestamps()
            ->orderBy('queued_songs.created_at');
    }

    public function currentSong(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'current_song_id');
    }

    public function cooldowns(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'cooldowns')
            ->withTimestamps();
    }

    public function activeCooldowns()
    {
        return $this->cooldowns()->wherePivot('created_at', '>', now()->subMinutes($this->cooldownMinutes()));
    }

    public function overdueCooldowns()
    {
        return $this->cooldowns()->wherePivot('created_at', '<', now()->subMinutes($this->cooldownMinutes()));
    }

    public function cooldownTimeHuman()
    {
        return now()->diffAsCarbonInterval(now()->addMinutes($this->cooldownMinutes()))->forHumans();
    }

    public function cooldownMinutes()
    {
        return $this->song_cooldown;
    }

    public function remainingTime()
    {
        return $this->song_endtime ? now()->diffInSeconds($this->song_endtime) : 0;
    }

    public function updatePlaystate()
    {
        $playstate = SpotifyService::api($this)->getMyCurrentPlaybackInfo();

        $this->song_endtime = $playstate->item->duration_ms ? now()->addSeconds(($playstate->item->duration_ms - $playstate->progress_ms) / 1000) : null;
        $this->is_playing = $playstate->is_playing;
        $this->current_song_id = $playstate->item->id;

        $this->save();

        Song::fetchAndSave($this->current_song_id, $this);
    }

    public function queueNextTrack()
    {
        $this->hadActionNow();

        $song = $this->queue()->first();
        if ($song === null) {
            return false;
        }

        SpotifyService::api($this)->queue($song->id);
        $this->queue()->detach($song->id);

        $this->cooldowns()->attach($song->id);

        return true;
    }

    public function history(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'history')
            ->withTimestamps()
            ->orderBy('history.created_at', 'desc');
    }

    public function addCurrentSongToHistory() {
        $this->history()->attach($this->current_song_id);
    }

    public function skip()
    {
        $this->queueNextTrack();
        $this->hadActionNow();
        SpotifyService::api($this)->next();
        CheckPlayback::dispatch($this);
    }

    public function url()
    {
        return route('jams', $this->id);
    }

    public function hadActionNow() {
        $this->last_action_at = now();
        $this->save();
    }

    public function isInactiveForTooLong() {
        return $this->last_action_at && now()->diffInMinutes($this->last_action_at) > 60;
    }

    public function generateQrCode()
    {
        if (file_exists(storage_path('app/public/qr-codes/'.$this->id.'.svg'))) {
            return;
        }

        QrCode::size(200)->margin(1)->generate($this->url(), storage_path('app/public/qr-codes/'.$this->id.'.svg'));
    }
}
