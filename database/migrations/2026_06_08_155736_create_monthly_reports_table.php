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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('period_year');
            $table->tinyInteger('period_month');
            $table->string('type', 40);
            $table->string('file_path', 255)->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('emailed_at')->nullable();
            $table->timestamps();

            $table->index(['period_year', 'period_month', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
