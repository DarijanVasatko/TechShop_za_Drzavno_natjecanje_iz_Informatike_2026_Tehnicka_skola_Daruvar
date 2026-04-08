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
        if (!Schema::hasColumn('narudzba', 'Adresa_dostave')) {
            Schema::table('narudzba', function (Blueprint $table) {
                 $table->string('Adresa_dostave', 500)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('narudzba', 'Adresa_dostave')) {
            Schema::table('narudzba', function (Blueprint $table) {
                $table->dropColumn('Adresa_dostave');
            });
        }
    }
};
