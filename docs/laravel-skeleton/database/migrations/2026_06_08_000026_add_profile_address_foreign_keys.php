<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Naknadno dodani FK-ovi (izbjegavanje cirkularne ovisnosti
     * addresses <-> customers/companies pri kreiranju tablica).
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('default_address_id')->references('id')->on('addresses')->nullOnDelete();
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('hq_address_id')->references('id')->on('addresses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', fn (Blueprint $t) => $t->dropForeign(['default_address_id']));
        Schema::table('companies', fn (Blueprint $t) => $t->dropForeign(['hq_address_id']));
    }
};
