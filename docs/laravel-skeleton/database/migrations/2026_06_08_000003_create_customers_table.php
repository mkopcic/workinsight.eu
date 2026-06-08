<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->char('public_id', 26)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->char('oib', 11)->nullable()->index();
            $table->string('phone', 30)->nullable();
            $table->unsignedBigInteger('default_address_id')->nullable(); // FK dodan kasnije
            $table->string('status', 20)->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void { Schema::dropIfExists('customers'); }
};
