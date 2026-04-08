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
        Schema::table('narudzba', function (Blueprint $table) {
            $table->foreignId('promo_kod_id')
                ->nullable()
                ->after('Ukupni_iznos')
                ->constrained('promo_kodovi')
                ->nullOnDelete();

            $table->decimal('iznos_popusta', 8, 2)->default(0)->after('promo_kod_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('narudzba', function (Blueprint $table) {
            $table->dropForeign(['promo_kod_id']);
            $table->dropColumn(['promo_kod_id', 'iznos_popusta']);
        });
    }
};
