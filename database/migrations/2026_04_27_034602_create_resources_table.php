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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_type_id')
                  ->constrained('resource_types')
                  ->restrictOnDelete();
            $table->string('name', 200);
            $table->string('code', 50)->nullable();           // kode unik opsional (mis: RM-01, EQ-002)
            $table->text('description')->nullable();
            $table->string('location', 200)->nullable();      // lokasi fisik (relevan untuk room/equipment)
            $table->unsignedInteger('capacity')->nullable();  // kapasitas (relevan untuk room/schedule)
            $table->decimal('price_per_unit', 12, 2)->default(0); // harga per unit (jam/hari/sesi)
            $table->string('price_unit', 20)->default('hour'); // hour, day, session, item
            $table->string('image')->nullable();
            $table->json('meta')->nullable();                 // {brand, model, serial_number, ...}
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
 
            $table->index('resource_type_id');
            $table->index('is_active');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
