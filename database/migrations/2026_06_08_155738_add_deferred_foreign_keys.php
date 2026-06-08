<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cross-table foreign keys are added here, after every table exists, so that
 * table-creation order never constrains relational integrity. ON DELETE rules:
 * RESTRICT for financial/historical links, CASCADE for true children, SET NULL
 * for optional references.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('default_line_id')->references('id')->on('delivery_lines')->nullOnDelete();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('default_address_id')->references('id')->on('addresses')->nullOnDelete();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('hq_address_id')->references('id')->on('addresses')->nullOnDelete();
        });

        Schema::table('company_contacts', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });

        Schema::table('pensioners', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();
            $table->foreign('delivery_line_id')->references('id')->on('delivery_lines')->nullOnDelete();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->foreign('meal_category_id')->references('id')->on('meal_categories')->nullOnDelete();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('menu_id')->references('id')->on('menus')->cascadeOnDelete();
            $table->foreign('meal_id')->references('id')->on('meals')->restrictOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->nullOnDelete();
            $table->foreign('ordered_by_user_id')->references('id')->on('users')->restrictOnDelete();
        });

        Schema::table('order_lines', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->nullOnDelete();
            $table->foreign('meal_id')->references('id')->on('meals')->restrictOnDelete();
        });

        Schema::table('delivery_lines', function (Blueprint $table) {
            $table->foreign('default_driver_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('line_assignments', function (Blueprint $table) {
            $table->foreign('delivery_line_id')->references('id')->on('delivery_lines')->cascadeOnDelete();
            $table->foreign('driver_user_id')->references('id')->on('users')->restrictOnDelete();
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->foreign('delivery_line_id')->references('id')->on('delivery_lines')->restrictOnDelete();
            $table->foreign('line_assignment_id')->references('id')->on('line_assignments')->nullOnDelete();
            $table->foreign('carried_over_from_id')->references('id')->on('deliveries')->nullOnDelete();
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
            $table->foreign('order_line_id')->references('id')->on('order_lines')->restrictOnDelete();
            $table->foreign('meal_id')->references('id')->on('meals')->restrictOnDelete();
        });

        Schema::table('delivery_status_logs', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
            $table->foreign('driver_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('driver_locations', function (Blueprint $table) {
            $table->foreign('driver_user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('invoice_export_lines', function (Blueprint $table) {
            $table->foreign('invoice_export_id')->references('id')->on('invoice_exports')->cascadeOnDelete();
        });

        Schema::table('delivery_daily_summaries', function (Blueprint $table) {
            $table->foreign('delivery_line_id')->references('id')->on('delivery_lines')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['default_line_id']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['default_address_id']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['owner_user_id']);
            $table->dropForeign(['hq_address_id']);
        });

        Schema::table('company_contacts', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('pensioners', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['delivery_line_id']);
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['meal_category_id']);
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['meal_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropForeign(['ordered_by_user_id']);
        });

        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['menu_item_id']);
            $table->dropForeign(['meal_id']);
        });

        Schema::table('delivery_lines', function (Blueprint $table) {
            $table->dropForeign(['default_driver_id']);
        });

        Schema::table('line_assignments', function (Blueprint $table) {
            $table->dropForeign(['delivery_line_id']);
            $table->dropForeign(['driver_user_id']);
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['delivery_line_id']);
            $table->dropForeign(['line_assignment_id']);
            $table->dropForeign(['carried_over_from_id']);
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
            $table->dropForeign(['order_line_id']);
            $table->dropForeign(['meal_id']);
        });

        Schema::table('delivery_status_logs', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
            $table->dropForeign(['driver_user_id']);
        });

        Schema::table('driver_locations', function (Blueprint $table) {
            $table->dropForeign(['driver_user_id']);
        });

        Schema::table('invoice_export_lines', function (Blueprint $table) {
            $table->dropForeign(['invoice_export_id']);
        });

        Schema::table('delivery_daily_summaries', function (Blueprint $table) {
            $table->dropForeign(['delivery_line_id']);
        });
    }
};
