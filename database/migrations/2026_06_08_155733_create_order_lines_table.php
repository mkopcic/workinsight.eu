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
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->date('delivery_date');
            $table->foreignId('menu_item_id')->nullable();
            $table->foreignId('meal_id');
            $table->nullableMorphs('beneficiary');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);
            $table->enum('status', ['pending', 'locked', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['delivery_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
