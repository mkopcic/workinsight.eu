<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Very high volume. Live positions live in Redis (Reverb); only downsampled
     * history is persisted here. Candidate for daily RANGE partitioning + pruning.
     */
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_user_id');
            $table->date('service_date');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('recorded_at', 3);

            $table->index(['driver_user_id', 'service_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};
