<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Materialized summary filled by a scheduled job so dashboards never scan
     * deliveries/order_lines live. One row per day / line / recipient type.
     */
    public function up(): void
    {
        Schema::create('delivery_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('service_date');
            $table->foreignId('delivery_line_id')->nullable();
            $table->string('recipient_type', 30);
            $table->unsignedInteger('meals_total')->default(0);
            $table->unsignedInteger('delivered_count')->default(0);
            $table->unsignedInteger('canister_count')->default(0);
            $table->unsignedInteger('no_answer_count')->default(0);
            $table->timestamps();

            $table->unique(['service_date', 'delivery_line_id', 'recipient_type'], 'delivery_daily_summary_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_daily_summaries');
    }
};
