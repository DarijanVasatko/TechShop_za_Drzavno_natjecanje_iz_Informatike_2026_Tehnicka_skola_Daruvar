<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalji_narudzbe', function (Blueprint $table) {
            $table->decimal('cijena_po_komadu', 10, 2)->after('Kolicina')->default(0);
        });

        DB::statement('
            UPDATE detalji_narudzbe dn
            JOIN proizvod p ON p.Proizvod_ID = dn.Proizvod_ID
            SET dn.cijena_po_komadu = p.Cijena
            WHERE dn.cijena_po_komadu = 0
        ');
    }

    public function down(): void
    {
        Schema::table('detalji_narudzbe', function (Blueprint $table) {
            $table->dropColumn('cijena_po_komadu');
        });
    }
};
