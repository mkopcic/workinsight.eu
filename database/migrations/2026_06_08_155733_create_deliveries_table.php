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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('service_date')->index();
            $table->foreignId('delivery_line_id')->index();
            $table->foreignId('line_assignment_id')->nullable();
            $table->morphs('recipient');
            $table->json('address_snapshot');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('stop_sequence')->nullable();
            $table->enum('status', ['pending', 'delivered', 'canister_collected', 'no_answer', 'rescheduled', 'failed'])->default('pending')->index();
            $table->foreignId('carried_over_from_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['service_date', 'delivery_line_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
