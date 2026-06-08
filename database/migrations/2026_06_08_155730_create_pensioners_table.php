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
        Schema::create('pensioners', function (Blueprint $table) {
            $table->id();
            $table->string('external_para_id', 64)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->foreignId('address_id')->nullable();
            $table->string('phone', 30)->nullable();
            $table->foreignId('delivery_line_id')->nullable()->index();
            $table->timestamp('para_synced_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensioners');
    }
};
