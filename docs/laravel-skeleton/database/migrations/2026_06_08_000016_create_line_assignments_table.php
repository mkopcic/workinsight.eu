<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('line_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_line_id')->constrained('delivery_lines')->cascadeOnDelete();
            $table->foreignId('driver_user_id')->constrained('users')->restrictOnDelete();
            $table->date('service_date');
            $table->string('status', 20)->default('planned');
            $table->timestamps();
            $table->unique(['delivery_line_id', 'service_date']);
            $table->index(['driver_user_id', 'service_date']);
        });
    }

    public function down(): void { Schema::dropIfExists('line_assignments'); }
};
