<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Materialized monthly billing rollup per billable (customer/company),
     * filled by a scheduled job to keep analytics constant-time.
     */
    public function up(): void
    {
        Schema::create('billing_monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->smallInteger('period_year');
            $table->tinyInteger('period_month');
            $table->unsignedInteger('orders_count')->default(0);
            $table->unsignedInteger('meals_count')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['billable_type', 'billable_id', 'period_year', 'period_month'], 'billing_monthly_summary_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_monthly_summaries');
    }
};
