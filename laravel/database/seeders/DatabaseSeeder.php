<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@techshop.tsd'], 
            [
                'ime'       => 'Admin',
                'prezime'   => 'User',
                'telefon'   => '000000000',
                'password'  => bcrypt('TlwK[(9}fQ1~g;Q7]xo-H~(J!Vz}.4PouVhrnH^ir,VK-aGqAG'), 
                'is_admin'  => true,
            ]
        );

        $this->call([
            KategorijaSeeder::class,
            NacinPlacanjaSeeder::class,
            PcComponentTypeSeeder::class,
            PcComponentsSeeder::class,
        ]);

        $this->call([
        BrandSeeder::class,
    ]);
    }
}
