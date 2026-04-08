<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            DELETE k1 FROM kosarica k1
            INNER JOIN kosarica k2
            WHERE k1.id > k2.id
              AND k1.korisnik_id = k2.korisnik_id
              AND k1.proizvod_id = k2.proizvod_id
        ");

        Schema::table('kosarica', function (Blueprint $table) {
            $table->unique(['korisnik_id', 'proizvod_id'], 'kosarica_user_product_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kosarica', function (Blueprint $table) {
            $table->dropUnique('kosarica_user_product_unique');
        });
    }
};
