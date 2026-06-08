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
        Schema::create('invoice_exports', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->smallInteger('period_year');
            $table->tinyInteger('period_month');
            $table->string('idempotency_key', 80)->unique();
            $table->json('payload');
            $table->string('external_invoice_id', 64)->nullable();
            $table->enum('status', ['pending', 'sent', 'failed', 'acknowledged'])->default('pending')->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['billable_type', 'billable_id', 'period_year', 'period_month'], 'invoice_exports_billable_period_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_exports');
    }
};
