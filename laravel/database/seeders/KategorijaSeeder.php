<?php

namespace Database\Seeders;

use App\Models\Kategorija;
use Illuminate\Database\Seeder;

class KategorijaSeeder extends Seeder
{
   
    public function run(): void
    {
        if (Kategorija::count() > 0) {
            $this->command->info('Kategorije već postoje, preskačem seeder.');
            return;
        }

        $kategorije = [
            'Laptopi',
            'Monitori',
            'Periferija',
            'Komponente',
            'Mrezna oprema',
            'Gaming',
            'Uredska oprema',
        ];

        foreach ($kategorije as $naziv) {
            Kategorija::firstOrCreate(
                ['ImeKategorija' => $naziv]
            );
        }

        $this->command->info('Kategorije seeded successfully!');
    }
}
