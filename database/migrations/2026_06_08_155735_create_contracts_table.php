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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->morphs('contractable');
            $table->string('contract_number', 30)->unique();
            $table->enum('status', ['draft', 'generated', 'sent', 'signed', 'expired', 'terminated'])->default('draft')->index();
            $table->string('generated_pdf_path', 255)->nullable();
            $table->string('signed_pdf_path', 255)->nullable();
            $table->date('valid_from');
            $table->date('valid_until')->index();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
