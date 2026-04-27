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
        Schema::create('booking_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();
            $table->foreignId('changed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->enum('from_status', [
                'pending', 'approved', 'rejected', 'cancelled', 'completed',
            ])->nullable();                                  // null jika log pertama (created)
            $table->enum('to_status', [
                'pending', 'approved', 'rejected', 'cancelled', 'completed',
            ]);
            $table->text('reason')->nullable();             // catatan/alasan perubahan
            $table->json('snapshot')->nullable();           // snapshot data booking saat itu
            $table->timestamps();
 
            $table->index('booking_id');
            $table->index('changed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_logs');
    }
};
