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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->nullable();
            $table->char('public_id', 26)->unique();
            $table->string('legal_name', 200);
            $table->char('oib', 11)->unique();
            $table->string('vat_id', 20)->nullable();
            $table->foreignId('hq_address_id')->nullable();
            $table->string('billing_email', 190);
            $table->unsignedSmallInteger('employee_count')->default(0);
            $table->enum('status', ['active', 'paused', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
