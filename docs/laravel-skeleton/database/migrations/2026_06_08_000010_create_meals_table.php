<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_category_id')->nullable()->constrained('meal_categories')->nullOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->json('allergens')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('meals'); }
};
