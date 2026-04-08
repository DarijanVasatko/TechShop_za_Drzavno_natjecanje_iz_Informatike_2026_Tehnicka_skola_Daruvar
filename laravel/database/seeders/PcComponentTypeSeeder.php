<?php

namespace Database\Seeders;

use App\Models\TipProizvoda;
use Illuminate\Database\Seeder;

class PcComponentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['naziv_tip' => 'Procesori',       'slug' => 'cpu',           'redoslijed' => 1, 'ikona' => 'bi-cpu',        'obavezan' => true],
            ['naziv_tip' => 'CPU Hladnjaci',   'slug' => 'cpu-cooler',    'redoslijed' => 2, 'ikona' => 'bi-fan',        'obavezan' => false],
            ['naziv_tip' => 'Matične ploče',    'slug' => 'maticna-ploca', 'redoslijed' => 3, 'ikona' => 'bi-motherboard','obavezan' => true],
            ['naziv_tip' => 'RAM memorija',     'slug' => 'ram',           'redoslijed' => 4, 'ikona' => 'bi-memory',     'obavezan' => true],
            ['naziv_tip' => 'Grafičke kartice', 'slug' => 'gpu',           'redoslijed' => 5, 'ikona' => 'bi-gpu-card',   'obavezan' => false],
            ['naziv_tip' => 'SSD i HDD',        'slug' => 'storage',       'redoslijed' => 6, 'ikona' => 'bi-device-hdd', 'obavezan' => true],
            ['naziv_tip' => 'Napajanja',        'slug' => 'napajanje',     'redoslijed' => 7, 'ikona' => 'bi-lightning',  'obavezan' => true],
            ['naziv_tip' => 'Kućišta',          'slug' => 'kuciste',       'redoslijed' => 8, 'ikona' => 'bi-pc-display', 'obavezan' => true],
        ];

        foreach ($types as $type) {
            TipProizvoda::updateOrCreate(
                ['slug' => $type['slug']],
                array_merge($type, ['konfigurator' => true])
            );
        }
    }
}
