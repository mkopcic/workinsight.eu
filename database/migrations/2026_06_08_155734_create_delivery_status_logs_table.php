<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Append-only audit (high volume). Candidate for monthly RANGE partitioning
     * on created_at in production.
     */
    public function up(): void
    {
        Schema::create('delivery_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->index();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->foreignId('driver_user_id')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_status_logs');
    }
};
