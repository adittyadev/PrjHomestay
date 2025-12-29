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
        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();

            // relasi ke komentar
            $table->foreignId('review_id')
                ->constrained('reviews')
                ->cascadeOnDelete();

            // user yang membalas
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // isi balasan
            $table->text('reply');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_replies');
    }
};
