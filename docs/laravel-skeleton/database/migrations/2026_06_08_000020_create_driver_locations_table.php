<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * VRLO VISOK VOLUMEN: live pozicija ide u Redis (Reverb).
     * Ovdje se sprema samo downsamplirana povijest, particionirano po DAY(service_date),
     * uz pruning starih particija (30-90 dana).
     */
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_user_id')->constrained('users')->cascadeOnDelete();
            $table->date('service_date')->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('recorded_at', 3);
            $table->index(['driver_user_id', 'service_date']);
        });
    }

    public function down(): void { Schema::dropIfExists('driver_locations'); }
};
