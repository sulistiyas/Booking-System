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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();
            $table->foreignId('resource_id')
                  ->constrained('resources')
                  ->restrictOnDelete();
 
            // Waktu spesifik per item (bisa berbeda dari booking utama)
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
 
            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit', 30)->default('hour');     // hour, day, session, item
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
 
            $table->text('notes')->nullable();               // catatan per item
            $table->json('meta')->nullable();                // data tambahan per item
 
            $table->timestamps();
 
            $table->index('booking_id');
            $table->index('resource_id');
            $table->index(['start_datetime', 'end_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
