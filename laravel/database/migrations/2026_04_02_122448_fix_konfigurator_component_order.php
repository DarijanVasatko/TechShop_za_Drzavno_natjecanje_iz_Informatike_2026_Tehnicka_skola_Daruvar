<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $order = [
        'cpu'           => 1,
        'cpu-cooler'    => 2,
        'maticna-ploca' => 3,
        'ram'           => 4,
        'gpu'           => 5,
        'storage'       => 6,
        'napajanje'     => 7,
        'kuciste'       => 8,
    ];

    public function up(): void
    {
        foreach ($this->order as $slug => $redoslijed) {
            DB::table('tip_proizvoda')
                ->where('slug', $slug)
                ->update(['redoslijed' => $redoslijed]);
        }
    }

    public function down(): void
    {
        $old = [
            'cpu'           => 1,
            'maticna-ploca' => 2,
            'cpu-cooler'    => 2,
            'ram'           => 3,
            'gpu'           => 4,
            'storage'       => 5,
            'napajanje'     => 6,
            'kuciste'       => 7,
        ];

        foreach ($old as $slug => $redoslijed) {
            DB::table('tip_proizvoda')
                ->where('slug', $slug)
                ->update(['redoslijed' => $redoslijed]);
        }
    }
};
