<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->date('delivery_date');
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->foreignId('meal_id')->constrained('meals')->restrictOnDelete();
            $table->nullableMorphs('beneficiary'); // zaposlenik / umirovljenik
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->index(['delivery_date', 'status']);
        });
    }

    public function down(): void { Schema::dropIfExists('order_lines'); }
};
