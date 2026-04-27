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
        Schema::create('resource_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();           // room, equipment, schedule, service
            $table->string('label', 150);                    // Ruangan, Peralatan, Jadwal, Layanan
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();         // heroicon name, misal: 'building-office'
            $table->string('color', 20)->default('blue');    // untuk badge warna di UI
            $table->boolean('requires_approval')->default(false); // apakah butuh approval admin/staff
            $table->boolean('allow_overlap')->default(false);    // apakah boleh booking overlapping
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
 
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_types');
    }
};
