<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('kategorija')
            ->where('ImeKategorija', 'Audi i Video')
            ->update(['ImeKategorija' => 'Audio i Video']);
    }

    public function down(): void
    {
        DB::table('kategorija')
            ->where('ImeKategorija', 'Audio i Video')
            ->update(['ImeKategorija' => 'Audi i Video']);
    }
};
