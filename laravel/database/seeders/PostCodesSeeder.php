<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCodesSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/post_codes_hr.csv');

        if (!file_exists($path)) {
            $this->command?->error("CSV file not found: {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->command?->error("Unable to open CSV: {$path}");
            return;
        }

        fgetcsv($handle);

        $batch = [];
        $seen = []; 
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            [$city, $postal, $country] = $row;

            $city = trim($city);
            $postal = trim($postal);
            $country = trim($country) ?: 'HR';

            if ($city === '' || $postal === '') {
                continue;
            }

            $key = mb_strtolower($city, 'UTF-8') . '|' . $postal . '|' . $country;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $batch[] = [
                'city' => $city,
                'postal_code' => $postal,
                'country' => $country,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= 500) {
                DB::table('post_codes')->upsert($batch, ['city', 'country'], ['postal_code', 'updated_at']);
                $batch = [];
            }
        }

        fclose($handle);

        if (!empty($batch)) {
            DB::table('post_codes')->upsert($batch, ['city', 'country'], ['postal_code', 'updated_at']);
        }
    }
}
