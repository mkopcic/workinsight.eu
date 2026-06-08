<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('para_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->string('source', 10)->default('api');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('records_processed')->default(0);
            $table->unsignedInteger('records_created')->default(0);
            $table->unsignedInteger('records_updated')->default(0);
            $table->string('status', 20)->default('running');
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('para_sync_runs'); }
};
