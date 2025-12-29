<?php

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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // relasi user yang memberi komentar
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // relasi kamar
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            // rating 1 - 5
            $table->tinyInteger('rating');

            // isi komentar
            $table->text('comment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
