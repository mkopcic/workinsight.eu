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
        Schema::create('delivery_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('code', 20)->unique();
            $table->string('zone', 60)->nullable();
            $table->char('color', 7)->nullable();
            $table->foreignId('default_driver_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_lines');
    }
};
