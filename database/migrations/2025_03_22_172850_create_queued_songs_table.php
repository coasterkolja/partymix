<?php

declare(strict_types=1);

use App\Models\Jam;
use App\Models\Song;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queued_songs', function (Blueprint $table) {
            $table->foreignIdFor(Jam::class);
            $table->foreignIdFor(Song::class);
            $table->timestamps();

            $table->primary(['jam_id', 'song_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queued_songs');
    }
};
