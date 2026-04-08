<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('narudzba')
            ->where('Status', 'Isporučeno')
            ->update(['Status' => 'Dostavljeno']);

        DB::table('narudzba')
            ->where('Status', 'Narudžba završena')
            ->update(['Status' => 'Dovršena']);
    }

    public function down(): void
    {
        DB::table('narudzba')
            ->where('Status', 'Dostavljeno')
            ->update(['Status' => 'Isporučeno']);

        DB::table('narudzba')
            ->where('Status', 'Dovršena')
            ->update(['Status' => 'Narudžba završena']);
    }
};
