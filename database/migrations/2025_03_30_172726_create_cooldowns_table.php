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
        Schema::create('cooldowns', function (Blueprint $table) {
            $table->foreignIdFor(Song::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Jam::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['song_id', 'jam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooldowns');
    }
};
