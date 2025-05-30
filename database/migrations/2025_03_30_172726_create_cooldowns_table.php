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
