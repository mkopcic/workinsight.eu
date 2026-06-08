<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_export_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_export_id')->constrained('invoice_exports')->cascadeOnDelete();
            $table->string('description', 255);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('invoice_export_lines'); }
};
