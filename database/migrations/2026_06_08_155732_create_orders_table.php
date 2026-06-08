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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->string('order_number', 20)->unique();
            $table->morphs('subscriber');
            $table->foreignId('subscription_id')->nullable();
            $table->enum('order_type', ['daily', 'weekly', 'monthly']);
            $table->enum('status', ['draft', 'confirmed', 'partially_delivered', 'completed', 'cancelled'])->default('draft')->index();
            $table->timestamp('placed_at')->nullable();
            $table->foreignId('ordered_by_user_id');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
