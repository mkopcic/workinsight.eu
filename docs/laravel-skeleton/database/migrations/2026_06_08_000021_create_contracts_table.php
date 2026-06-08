<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 26)->unique();
            $table->morphs('contractable'); // customer / company
            $table->string('contract_number', 30)->unique();
            $table->string('status', 20)->default('draft')->index();
            $table->string('generated_pdf_path', 255)->nullable();
            $table->string('signed_pdf_path', 255)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable()->index();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void { Schema::dropIfExists('contracts'); }
};
