<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->date('delivery_date')->index();
            $table->foreignId('meal_id')->constrained('meals')->restrictOnDelete();
            $table->string('slot', 20)->default('main');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
            $table->index(['menu_id', 'delivery_date']);
        });
    }

    public function down(): void { Schema::dropIfExists('menu_items'); }
};
