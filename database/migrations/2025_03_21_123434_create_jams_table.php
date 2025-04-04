<?php

use App\Models\Jam;
use App\Models\Song;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jams', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('access_token');
            $table->string('refresh_token');
            $table->dateTime('expiration_date');
            $table->boolean('is_playing')->default(false);
            $table->foreignIdFor(Song::class, 'current_song_id')->nullable();
            $table->datetime('song_endtime')->nullable();
            $table->dateTime('last_action_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jams');
    }
};
