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
            $table->string('booking_number', 30)->unique();   // BKG-2024-0001
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('title', 200);                    // judul/keperluan booking
            $table->text('notes')->nullable();               // catatan dari user
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'completed',
            ])->default('pending');
            $table->text('rejection_reason')->nullable();    // alasan jika rejected
            $table->text('cancellation_reason')->nullable(); // alasan jika cancelled
 
            // Waktu booking keseluruhan (aggregate dari semua items)
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
 
            // Pricing summary
            $table->decimal('total_price', 14, 2)->default(0);
            $table->enum('payment_status', [
                'unpaid',
                'partial',
                'paid',
                'refunded',
            ])->default('unpaid');
 
            // Approval tracking
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('rejected_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('rejected_at')->nullable();
 
            $table->json('meta')->nullable();                // data tambahan fleksibel
            $table->timestamps();
            $table->softDeletes();
 
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index(['start_datetime', 'end_datetime']);
            $table->index('booking_number');
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
