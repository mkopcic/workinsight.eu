<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('subscriber'); // subscriber_type, subscriber_id
            $table->string('menu_type', 20);
            $table->string('plan', 20);
            $table->json('weekday_pattern')->nullable();
            $table->unsignedSmallInteger('default_quantity')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('subscriptions'); }
};
