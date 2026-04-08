<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'AMD', 'Intel', 'Nvidia', 'ASUS', 'Gigabyte', 'MSI', 
            'ASRock', 'EVGA', 'Sapphire', 'Zotac', 'PowerColor', 'Colorful',

            'Samsung', 'Western Digital', 'Seagate', 'Kingston', 'Crucial', 
            'Corsair', 'G.Skill', 'ADATA', 'XPG', 'Sabrent', 'Transcend', 
            'PNY', 'SanDisk',

            'Logitech', 'Razer', 'SteelSeries', 'HyperX', 'Keychron', 
            'Ducky', 'Glorious', 'BenQ', 'ZOWIE', 'Audio-Technica', 'Sennheiser',

            'Noctua', 'Cooler Master', 'NZXT', 'Fractal Design', 'be quiet!', 
            'Lian Li', 'DeepCool', 'Arctic', 'SeaSonic', 'Thermaltake',

            'Dell', 'Alienware', 'LG', 'AOC', 'ViewSonic', 'Acer', 'Predator', 'Philips',

            'TP-Link', 'Ubiquiti', 'Netgear', 'Cisco', 'Apple', 'Google', 'Xiaomi'
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->updateOrInsert(
                ['name' => $brand],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}