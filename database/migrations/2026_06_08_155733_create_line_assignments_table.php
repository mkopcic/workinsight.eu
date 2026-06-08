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
        Schema::create('line_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_line_id');
            $table->foreignId('driver_user_id');
            $table->date('service_date');
            $table->enum('status', ['planned', 'in_progress', 'completed'])->default('planned');
            $table->timestamps();

            $table->unique(['delivery_line_id', 'service_date']);
            $table->index(['driver_user_id', 'service_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_assignments');
    }
};
