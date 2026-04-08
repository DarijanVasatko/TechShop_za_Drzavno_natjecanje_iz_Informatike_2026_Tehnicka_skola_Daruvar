<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tip_proizvoda')
            ->where('id_tip', 58)
            ->update([
                'konfigurator' => true,
                'slug'         => 'cpu-cooler',
                'redoslijed'   => 2,
                'ikona'        => 'bi-fan',
                'obavezan'     => false,
            ]);
    }

    public function down(): void
    {
        DB::table('tip_proizvoda')
            ->where('id_tip', 58)
            ->update([
                'konfigurator' => false,
                'slug'         => null,
                'redoslijed'   => 0,
                'ikona'        => null,
                'obavezan'     => true,
            ]);
    }
};
