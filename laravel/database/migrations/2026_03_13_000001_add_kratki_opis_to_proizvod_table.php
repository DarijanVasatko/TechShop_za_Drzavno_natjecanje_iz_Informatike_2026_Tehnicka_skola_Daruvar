<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proizvod', function (Blueprint $table) {
            $table->string('KratkiOpis', 255)->nullable()->after('Opis');
        });
    }

    public function down(): void
    {
        Schema::table('proizvod', function (Blueprint $table) {
            $table->dropColumn('KratkiOpis');
        });
    }
};
