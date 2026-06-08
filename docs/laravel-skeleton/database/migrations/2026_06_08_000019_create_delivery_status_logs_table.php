<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * VISOK VOLUMEN: kandidat za RANGE particioniranje po MONTH(created_at).
     * Particioniranje se primjenjuje sirovim ALTER TABLE-om nakon kreiranja
     * (Laravel schema builder ne podrzava particije nativno).
     */
    public function up(): void
    {
        Schema::create('delivery_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('deliveries')->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void { Schema::dropIfExists('delivery_status_logs'); }
};
