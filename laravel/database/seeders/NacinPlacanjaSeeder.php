<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NacinPlacanjaSeeder extends Seeder
{
    public function run(): void
    {
        $metode = [
            ['NacinPlacanja_ID' => 1, 'Opis' => 'Gotovina pri preuzimanju'],
            ['NacinPlacanja_ID' => 2, 'Opis' => 'Bankovna uputnica'],
            ['NacinPlacanja_ID' => 3, 'Opis' => 'PayPal'],
            ['NacinPlacanja_ID' => 4, 'Opis' => 'Obročno plaćanje'],
            ['NacinPlacanja_ID' => 5, 'Opis' => 'Kripto valuta'],
            ['NacinPlacanja_ID' => 6, 'Opis' => 'Poklon bon'],
            ['NacinPlacanja_ID' => 7, 'Opis' => 'Kartica'],
        ];

        foreach ($metode as $metoda) {
            DB::table('nacin_placanja')->upsert(
                $metoda,
                ['NacinPlacanja_ID'],
                ['Opis']
            );
        }

        $this->command->info('Načini plaćanja seeded — kartica je ID ' . config('shop.card_payment_id') . '.');
    }
}
