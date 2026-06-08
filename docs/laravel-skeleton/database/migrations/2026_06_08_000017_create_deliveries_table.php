<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('service_date');
            $table->foreignId('delivery_line_id')->constrained('delivery_lines')->restrictOnDelete();
            $table->foreignId('line_assignment_id')->nullable()->constrained('line_assignments')->nullOnDelete();
            $table->morphs('recipient'); // customer / company / pensioner
            $table->json('address_snapshot')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('stop_sequence')->default(0);
            $table->string('status', 30)->default('pending');
            $table->foreignId('carried_over_from_id')->nullable()->constrained('deliveries')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            // glavni indeks za dnevni pregled rute/admina
            $table->index(['service_date', 'delivery_line_id', 'status']);
        });
    }

    public function down(): void { Schema::dropIfExists('deliveries'); }
};
