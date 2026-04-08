<?php

namespace Database\Seeders;

use App\Models\Kategorija;
use App\Models\TipProizvoda;
use App\Models\Proizvod;
use App\Models\PcComponentSpec;
use Illuminate\Database\Seeder;

class PcComponentsSeeder extends Seeder
{
    private $sifraCounts = [];

  
    private function generateSifra(string $prefix): string
    {
        if (!isset($this->sifraCounts[$prefix])) {
            $this->sifraCounts[$prefix] = 1;
        }
        return $prefix . '-' . str_pad($this->sifraCounts[$prefix]++, 3, '0', STR_PAD_LEFT);
    }

   
    private function generateImagePath(string $naziv, string $folder): string
    {
        $slug = strtolower($naziv);
        $slug = str_replace([' ', '(', ')', '+', '.', '!'], ['-', '', '', '-', '', ''], $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return "uploads/pc-components/{$folder}/{$slug}.jpg";
    }

    
    public function run(): void
    {
        $kategorija = Kategorija::where('ImeKategorija', 'Komponente')->first();
        if (!$kategorija) {
            $maxId = Kategorija::max('id_kategorija') ?? 0;
            $kategorija = new Kategorija();
            $kategorija->id_kategorija = $maxId + 1;
            $kategorija->ImeKategorija = 'PC Komponente';
            $kategorija->save();
        }

        $tipCpu = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'Procesori'],
            ['naziv_tip' => 'Procesori', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipMotherboard = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'Matične ploče'],
            ['naziv_tip' => 'Matične ploče', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipRam = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'RAM memorija'],
            ['naziv_tip' => 'RAM memorija', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipGpu = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'Grafičke kartice'],
            ['naziv_tip' => 'Grafičke kartice', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipStorage = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'SSD i HDD'],
            ['naziv_tip' => 'SSD i HDD', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipPsu = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'Napajanja'],
            ['naziv_tip' => 'Napajanja', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipCooler = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'CPU Hladnjaci'],
            ['naziv_tip' => 'CPU Hladnjaci', 'kategorija_id' => $kategorija->id_kategorija]
        );
        $tipCase = TipProizvoda::updateOrCreate(
            ['naziv_tip' => 'Kućišta'],
            ['naziv_tip' => 'Kućišta', 'kategorija_id' => $kategorija->id_kategorija]
        );

        $cpus = [
            ['naziv' => 'Intel Core i3-12100F', 'cijena' => 99.99, 'opis' => '4 jezgre, 8 threadova, 3.3GHz base, 4.3GHz boost', 'socket' => 'LGA1700', 'tdp' => 58],
            ['naziv' => 'Intel Core i5-12400F', 'cijena' => 169.99, 'opis' => '6 jezgri, 12 threadova, 2.5GHz base, 4.4GHz boost', 'socket' => 'LGA1700', 'tdp' => 65],
            ['naziv' => 'Intel Core i5-13600K', 'cijena' => 299.99, 'opis' => '14 jezgri (6P+8E), 20 threadova, 3.5GHz base, 5.1GHz boost', 'socket' => 'LGA1700', 'tdp' => 125],
            ['naziv' => 'Intel Core i7-13700K', 'cijena' => 419.99, 'opis' => '16 jezgri (8P+8E), 24 threada, 3.4GHz base, 5.4GHz boost', 'socket' => 'LGA1700', 'tdp' => 125],
            ['naziv' => 'AMD Ryzen 5 5600', 'cijena' => 139.99, 'opis' => '6 jezgri, 12 threadova, 3.5GHz base, 4.4GHz boost', 'socket' => 'AM4', 'tdp' => 65],
            ['naziv' => 'AMD Ryzen 5 5600X', 'cijena' => 179.99, 'opis' => '6 jezgri, 12 threadova, 3.7GHz base, 4.6GHz boost', 'socket' => 'AM4', 'tdp' => 65],
            ['naziv' => 'AMD Ryzen 7 5800X', 'cijena' => 249.99, 'opis' => '8 jezgri, 16 threadova, 3.8GHz base, 4.7GHz boost', 'socket' => 'AM4', 'tdp' => 105],
            ['naziv' => 'AMD Ryzen 5 7600', 'cijena' => 229.99, 'opis' => '6 jezgri, 12 threadova, 3.8GHz base, 5.1GHz boost, Zen 4', 'socket' => 'AM5', 'tdp' => 65],
            ['naziv' => 'AMD Ryzen 7 7700X', 'cijena' => 349.99, 'opis' => '8 jezgri, 16 threadova, 4.5GHz base, 5.4GHz boost, Zen 4', 'socket' => 'AM5', 'tdp' => 105],
        ];

        foreach ($cpus as $cpu) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $cpu['naziv']],
                [
                    'sifra' => $this->generateSifra('CPU'),
                    'Naziv' => $cpu['naziv'],
                    'Cijena' => $cpu['cijena'],
                    'Opis' => $cpu['opis'],
                    'KratkiOpis' => 'Procesor ' . $cpu['socket'],
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipCpu->id_tip,
                    'StanjeNaSkladistu' => rand(5, 50),
                    'Slika' => $this->generateImagePath($cpu['naziv'], 'cpu'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipCpu->id_tip,
                    'socket_type' => $cpu['socket'],
                    'tdp' => $cpu['tdp'],
                ]
            );
        }

        $coolers = [
            ['naziv' => 'Intel Laminar RM1',                   'cijena' => 14.99,  'opis' => 'Stock hladnjak za Intel LGA1700, aluminijski, do 65W TDP',                          'socket' => 'LGA1700', 'tdp' => 5],
            ['naziv' => 'DeepCool AK400 LGA1700',              'cijena' => 29.99,  'opis' => 'Tower hladnjak, 4 heatpipe-a, 120mm ventilator, do 220W TDP',                        'socket' => 'LGA1700', 'tdp' => 8],
            ['naziv' => 'Arctic Liquid Freezer II 240 LGA1700', 'cijena' => 79.99,  'opis' => 'AIO vodeno hlađenje, 240mm radijator, tihi rad, do 250W TDP',                       'socket' => 'LGA1700', 'tdp' => 12],
            ['naziv' => 'AMD Wraith Stealth',                  'cijena' => 14.99,  'opis' => 'Stock hladnjak za AMD AM4, aluminijski, do 65W TDP',                                 'socket' => 'AM4',     'tdp' => 5],
            ['naziv' => 'be quiet! Pure Rock 2 AM4',           'cijena' => 34.99,  'opis' => 'Tower hladnjak, 4 heatpipe-a, 120mm Pure Wings 2, do 150W TDP',                      'socket' => 'AM4',     'tdp' => 7],
            ['naziv' => 'Noctua NH-D15 AM4',                   'cijena' => 89.99,  'opis' => 'Premium dual-tower, 2x 140mm NF-A15 ventilatora, do 250W TDP, 6 godina garancije',   'socket' => 'AM4',     'tdp' => 10],
            ['naziv' => 'AMD Wraith Prism',                    'cijena' => 19.99,  'opis' => 'Stock RGB hladnjak za AMD AM5, bakrena baza, do 105W TDP',                           'socket' => 'AM5',     'tdp' => 6],
            ['naziv' => 'DeepCool AK620 AM5',                  'cijena' => 54.99,  'opis' => 'Dual-tower hladnjak, 6 heatpipe-ova, 2x 120mm, do 260W TDP',                        'socket' => 'AM5',     'tdp' => 9],
            ['naziv' => 'NZXT Kraken X53 AM5',                 'cijena' => 129.99, 'opis' => 'AIO vodeno hlađenje, 240mm radijator, LCD zaslon s temperaturom, do 300W TDP',       'socket' => 'AM5',     'tdp' => 12],
        ];

        foreach ($coolers as $cooler) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $cooler['naziv']],
                [
                    'sifra' => $this->generateSifra('COOL'),
                    'Naziv' => $cooler['naziv'],
                    'Cijena' => $cooler['cijena'],
                    'Opis' => $cooler['opis'],
                    'KratkiOpis' => 'CPU hladnjak ' . $cooler['socket'],
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipCooler->id_tip,
                    'StanjeNaSkladistu' => rand(5, 40),
                    'Slika' => $this->generateImagePath($cooler['naziv'], 'cpu-cooler'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipCooler->id_tip,
                    'socket_type' => $cooler['socket'],
                    'tdp' => $cooler['tdp'],
                ]
            );
        }

        $motherboards = [
            ['naziv' => 'Gigabyte B660M DS3H', 'cijena' => 99.99, 'opis' => 'Intel B660, LGA1700, DDR4, mATX', 'socket' => 'LGA1700', 'ram' => 'DDR4', 'form' => 'mATX'],
            ['naziv' => 'MSI PRO B660M-A', 'cijena' => 119.99, 'opis' => 'Intel B660, LGA1700, DDR4, mATX, 2x M.2', 'socket' => 'LGA1700', 'ram' => 'DDR4', 'form' => 'mATX'],
            ['naziv' => 'ASUS TUF Gaming B760-PLUS', 'cijena' => 169.99, 'opis' => 'Intel B760, LGA1700, DDR5, ATX, WiFi ready', 'socket' => 'LGA1700', 'ram' => 'DDR5', 'form' => 'ATX'],
            ['naziv' => 'Gigabyte B550M DS3H', 'cijena' => 89.99, 'opis' => 'AMD B550, AM4, DDR4, mATX', 'socket' => 'AM4', 'ram' => 'DDR4', 'form' => 'mATX'],
            ['naziv' => 'MSI B550-A PRO', 'cijena' => 119.99, 'opis' => 'AMD B550, AM4, DDR4, ATX, PCIe 4.0', 'socket' => 'AM4', 'ram' => 'DDR4', 'form' => 'ATX'],
            ['naziv' => 'ASUS ROG STRIX B550-F', 'cijena' => 179.99, 'opis' => 'AMD B550, AM4, DDR4, ATX, WiFi 6', 'socket' => 'AM4', 'ram' => 'DDR4', 'form' => 'ATX'],
            ['naziv' => 'Gigabyte B650M DS3H', 'cijena' => 139.99, 'opis' => 'AMD B650, AM5, DDR5, mATX', 'socket' => 'AM5', 'ram' => 'DDR5', 'form' => 'mATX'],
            ['naziv' => 'MSI PRO B650-P WIFI', 'cijena' => 189.99, 'opis' => 'AMD B650, AM5, DDR5, ATX, WiFi 6E', 'socket' => 'AM5', 'ram' => 'DDR5', 'form' => 'ATX'],
        ];

        foreach ($motherboards as $mb) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $mb['naziv']],
                [
                    'sifra' => $this->generateSifra('MB'),
                    'Naziv' => $mb['naziv'],
                    'Cijena' => $mb['cijena'],
                    'Opis' => $mb['opis'],
                    'KratkiOpis' => 'Matična ploča ' . $mb['socket'],
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipMotherboard->id_tip,
                    'StanjeNaSkladistu' => rand(5, 30),
                    'Slika' => $this->generateImagePath($mb['naziv'], 'motherboard'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipMotherboard->id_tip,
                    'socket_type' => $mb['socket'],
                    'ram_type' => $mb['ram'],
                    'form_factor' => $mb['form'],
                ]
            );
        }

        $rams = [
            ['naziv' => 'Kingston Fury Beast 16GB (2x8GB) DDR4-3200', 'cijena' => 49.99, 'opis' => 'DDR4, 3200MHz, CL16, XMP 2.0', 'ram' => 'DDR4'],
            ['naziv' => 'Corsair Vengeance LPX 16GB (2x8GB) DDR4-3600', 'cijena' => 59.99, 'opis' => 'DDR4, 3600MHz, CL18, optimizirano za AMD/Intel', 'ram' => 'DDR4'],
            ['naziv' => 'G.Skill Ripjaws V 32GB (2x16GB) DDR4-3200', 'cijena' => 89.99, 'opis' => 'DDR4, 3200MHz, CL16, Intel XMP ready', 'ram' => 'DDR4'],
            ['naziv' => 'Kingston Fury Beast 16GB (2x8GB) DDR5-5200', 'cijena' => 79.99, 'opis' => 'DDR5, 5200MHz, CL40, on-die ECC', 'ram' => 'DDR5'],
            ['naziv' => 'Corsair Vengeance 32GB (2x16GB) DDR5-5600', 'cijena' => 129.99, 'opis' => 'DDR5, 5600MHz, CL36, Intel XMP 3.0', 'ram' => 'DDR5'],
            ['naziv' => 'G.Skill Trident Z5 32GB (2x16GB) DDR5-6000', 'cijena' => 159.99, 'opis' => 'DDR5, 6000MHz, CL30, RGB', 'ram' => 'DDR5'],
        ];

        foreach ($rams as $ram) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $ram['naziv']],
                [
                    'sifra' => $this->generateSifra('RAM'),
                    'Naziv' => $ram['naziv'],
                    'Cijena' => $ram['cijena'],
                    'Opis' => $ram['opis'],
                    'KratkiOpis' => 'RAM memorija ' . $ram['ram'],
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipRam->id_tip,
                    'StanjeNaSkladistu' => rand(10, 100),
                    'Slika' => $this->generateImagePath($ram['naziv'], 'ram'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipRam->id_tip,
                    'ram_type' => $ram['ram'],
                ]
            );
        }

        $gpus = [
            ['naziv' => 'NVIDIA GeForce GTX 1650', 'cijena' => 159.99, 'opis' => '4GB GDDR6, Turing, 896 CUDA jezgri', 'tdp' => 75],
            ['naziv' => 'AMD Radeon RX 6500 XT', 'cijena' => 169.99, 'opis' => '4GB GDDR6, RDNA 2, 1024 stream procesora', 'tdp' => 107],
            ['naziv' => 'NVIDIA GeForce RTX 3060', 'cijena' => 299.99, 'opis' => '12GB GDDR6, Ampere, 3584 CUDA jezgri, Ray Tracing', 'tdp' => 170],
            ['naziv' => 'AMD Radeon RX 6650 XT', 'cijena' => 279.99, 'opis' => '8GB GDDR6, RDNA 2, 2048 stream procesora', 'tdp' => 180],
            ['naziv' => 'NVIDIA GeForce RTX 4060', 'cijena' => 329.99, 'opis' => '8GB GDDR6, Ada Lovelace, DLSS 3, Ray Tracing', 'tdp' => 115],
            ['naziv' => 'AMD Radeon RX 7600', 'cijena' => 299.99, 'opis' => '8GB GDDR6, RDNA 3, 2048 stream procesora', 'tdp' => 165],
            ['naziv' => 'NVIDIA GeForce RTX 4070', 'cijena' => 599.99, 'opis' => '12GB GDDR6X, Ada Lovelace, DLSS 3, vrhunske performanse', 'tdp' => 200],
            ['naziv' => 'AMD Radeon RX 7800 XT', 'cijena' => 549.99, 'opis' => '16GB GDDR6, RDNA 3, 3840 stream procesora', 'tdp' => 263],
        ];

        foreach ($gpus as $gpu) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $gpu['naziv']],
                [
                    'sifra' => $this->generateSifra('GPU'),
                    'Naziv' => $gpu['naziv'],
                    'Cijena' => $gpu['cijena'],
                    'Opis' => $gpu['opis'],
                    'KratkiOpis' => 'Grafička kartica',
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipGpu->id_tip,
                    'StanjeNaSkladistu' => rand(3, 20),
                    'Slika' => $this->generateImagePath($gpu['naziv'], 'gpu'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipGpu->id_tip,
                    'tdp' => $gpu['tdp'],
                ]
            );
        }

        $storages = [
            ['naziv' => 'Kingston NV2 500GB NVMe', 'cijena' => 39.99, 'opis' => 'M.2 NVMe PCIe 4.0, do 3500MB/s čitanje'],
            ['naziv' => 'Samsung 980 500GB NVMe', 'cijena' => 54.99, 'opis' => 'M.2 NVMe PCIe 3.0, do 3100MB/s čitanje'],
            ['naziv' => 'WD Blue SN570 1TB NVMe', 'cijena' => 69.99, 'opis' => 'M.2 NVMe PCIe 3.0, do 3500MB/s čitanje'],
            ['naziv' => 'Samsung 970 EVO Plus 1TB', 'cijena' => 89.99, 'opis' => 'M.2 NVMe PCIe 3.0, do 3500MB/s, V-NAND'],
            ['naziv' => 'Seagate Barracuda 2TB HDD', 'cijena' => 59.99, 'opis' => '3.5" SATA III, 7200 RPM, 256MB cache'],
            ['naziv' => 'WD Black SN850X 1TB NVMe', 'cijena' => 109.99, 'opis' => 'M.2 NVMe PCIe 4.0, do 7300MB/s čitanje, gaming'],
        ];

        foreach ($storages as $storage) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $storage['naziv']],
                [
                    'sifra' => $this->generateSifra('SSD'),
                    'Naziv' => $storage['naziv'],
                    'Cijena' => $storage['cijena'],
                    'Opis' => $storage['opis'],
                    'KratkiOpis' => 'Pohrana podataka',
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipStorage->id_tip,
                    'StanjeNaSkladistu' => rand(10, 80),
                    'Slika' => $this->generateImagePath($storage['naziv'], 'storage'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipStorage->id_tip,
                ]
            );
        }

        $psus = [
            ['naziv' => 'be quiet! System Power 10 450W', 'cijena' => 49.99, 'opis' => '450W, 80+ Bronze, tihi rad', 'wattage' => 450],
            ['naziv' => 'Corsair CV550 550W', 'cijena' => 54.99, 'opis' => '550W, 80+ Bronze, crni kablovi', 'wattage' => 550],
            ['naziv' => 'EVGA 600 BQ 600W', 'cijena' => 59.99, 'opis' => '600W, 80+ Bronze, semi-modularno', 'wattage' => 600],
            ['naziv' => 'be quiet! Pure Power 11 650W', 'cijena' => 79.99, 'opis' => '650W, 80+ Gold, tihi 120mm ventilator', 'wattage' => 650],
            ['naziv' => 'Corsair RM750 750W', 'cijena' => 99.99, 'opis' => '750W, 80+ Gold, potpuno modularno', 'wattage' => 750],
            ['naziv' => 'Seasonic Focus GX-850 850W', 'cijena' => 129.99, 'opis' => '850W, 80+ Gold, potpuno modularno, 10 godina garancije', 'wattage' => 850],
        ];

        foreach ($psus as $psu) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $psu['naziv']],
                [
                    'sifra' => $this->generateSifra('PSU'),
                    'Naziv' => $psu['naziv'],
                    'Cijena' => $psu['cijena'],
                    'Opis' => $psu['opis'],
                    'KratkiOpis' => 'Napajanje ' . $psu['wattage'] . 'W',
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipPsu->id_tip,
                    'StanjeNaSkladistu' => rand(5, 40),
                    'Slika' => $this->generateImagePath($psu['naziv'], 'psu'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipPsu->id_tip,
                    'wattage' => $psu['wattage'],
                ]
            );
        }

        $cases = [
            ['naziv' => 'Aerocool Cylon Mini', 'cijena' => 39.99, 'opis' => 'mATX, prozirna bočna stranica, RGB prednji panel', 'form' => 'mATX'],
            ['naziv' => 'DeepCool MATREXX 40', 'cijena' => 49.99, 'opis' => 'mATX, kaljeno staklo, mesh prednja ploča', 'form' => 'mATX'],
            ['naziv' => 'NZXT H510', 'cijena' => 79.99, 'opis' => 'ATX, minimalistički dizajn, kaljeno staklo', 'form' => 'ATX'],
            ['naziv' => 'Corsair 4000D Airflow', 'cijena' => 99.99, 'opis' => 'ATX, visok protok zraka, prostor za 360mm radijator', 'form' => 'ATX'],
            ['naziv' => 'Fractal Design Pop Air', 'cijena' => 89.99, 'opis' => 'ATX, mesh prednja ploča, izvrsno hlađenje', 'form' => 'ATX'],
            ['naziv' => 'Lian Li Lancool II Mesh', 'cijena' => 109.99, 'opis' => 'ATX, RGB, prostor za E-ATX, vrhunski airflow', 'form' => 'ATX'],
        ];

        foreach ($cases as $case) {
            $product = Proizvod::updateOrCreate(
                ['Naziv' => $case['naziv']],
                [
                    'sifra' => $this->generateSifra('CASE'),
                    'Naziv' => $case['naziv'],
                    'Cijena' => $case['cijena'],
                    'Opis' => $case['opis'],
                    'KratkiOpis' => 'Kućište ' . $case['form'],
                    'kategorija' => $kategorija->id_kategorija,
                    'tip_proizvoda_id' => $tipCase->id_tip,
                    'StanjeNaSkladistu' => rand(5, 25),
                    'Slika' => $this->generateImagePath($case['naziv'], 'case'),
                ]
            );

            PcComponentSpec::updateOrCreate(
                ['proizvod_id' => $product->Proizvod_ID],
                [
                    'proizvod_id' => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tipCase->id_tip,
                    'form_factor' => $case['form'],
                ]
            );
        }

        $this->command->info('PC Components seeded successfully!');
    }
}
