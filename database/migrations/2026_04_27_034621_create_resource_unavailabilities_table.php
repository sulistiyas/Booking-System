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
        Schema::create('resource_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                  ->constrained('resources')
                  ->cascadeOnDelete();
            $table->string('reason', 200)->nullable();       // alasan tidak tersedia
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule')->nullable();   // RRULE string (opsional, future enhancement)
            $table->timestamps();
 
            $table->index('resource_id');
            $table->index(['start_datetime', 'end_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_unavailabilities');
    }
};
