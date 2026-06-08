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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('subscriber');
            $table->enum('menu_type', ['pensioner', 'standard']);
            $table->enum('plan', ['daily', 'monthly']);
            $table->json('weekday_pattern');
            $table->unsignedSmallInteger('default_quantity')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'paused', 'ended'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
