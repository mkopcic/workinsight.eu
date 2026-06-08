<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_exports', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable'); // company / customer
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->string('idempotency_key', 80)->unique();
            $table->json('payload')->nullable();
            $table->string('external_invoice_id', 64)->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('invoice_exports'); }
};
