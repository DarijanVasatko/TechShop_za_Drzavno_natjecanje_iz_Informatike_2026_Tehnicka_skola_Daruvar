<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $remap = [
        4  => 54,  
        5  => 52,  
        6  => 53,  
        10 => 56,  
        11 => 57,  
    ];

    public function up(): void
    {
        foreach ($this->remap as $oldId => $newId) {
            DB::table('proizvod')
                ->where('tip_id', $oldId)
                ->update(['tip_id' => $newId]);
        }

        DB::table('tip_proizvoda')
            ->whereIn('id_tip', array_keys($this->remap))
            ->delete();
    }

    public function down(): void
    {
        $originals = [
            ['id_tip' => 4,  'naziv_tip' => 'Grafička kartica', 'kategorija_id' => 3],
            ['id_tip' => 5,  'naziv_tip' => 'Procesor',         'kategorija_id' => 3],
            ['id_tip' => 6,  'naziv_tip' => 'Matična ploča',    'kategorija_id' => 3],
            ['id_tip' => 10, 'naziv_tip' => 'Napajanje',        'kategorija_id' => 3],
            ['id_tip' => 11, 'naziv_tip' => 'Kučište',          'kategorija_id' => 3],
        ];

        foreach ($originals as $row) {
            DB::table('tip_proizvoda')->insert($row);
        }

        foreach ($this->remap as $oldId => $newId) {
            DB::table('proizvod')
                ->where('tip_id', $newId)
                ->update(['tip_id' => $oldId]);
        }
    }
};
