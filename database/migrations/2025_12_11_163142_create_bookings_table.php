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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamu_id')->constrained('tamus')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->dateTime('tanggal_booking')->useCurrent();
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('total_bayar');
            $table->enum('status_booking', [
                'pending',
                'booked',
                'checkin',
                'checkout',
                'selesai',
                'cancelled'
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
