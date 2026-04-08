<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proizvod;
use App\Models\Kategorija;
use App\Models\TipProizvoda;
use App\Models\PcComponentSpec;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private array $folderMap = [
        'cpu'           => 'cpu',
        'maticna-ploca' => 'motherboard',
        'ram'           => 'ram',
        'gpu'           => 'gpu',
        'storage'       => 'storage',
        'napajanje'     => 'psu',
        'kuciste'       => 'case',
    ];

    public function index(Request $request)
    {
        $q = $request->input('q');
        $category = $request->input('category');
        $konfigurator = $request->input('konfigurator');

        $query = Proizvod::with(['kategorija', 'brand', 'tip', 'pcSpec']);

        if ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('Naziv', 'LIKE', '%' . $q . '%')
                    ->orWhere('sifra', 'LIKE', '%' . $q . '%');
            });
        }

        if ($category) {
            $query->where('kategorija', $category);
        }

        if ($konfigurator === '1') {
            $query->whereHas('pcSpec');
        } elseif ($konfigurator === '0') {
            $query->whereDoesntHave('pcSpec');
        }

        $products = $query->orderByDesc('Proizvod_ID')->get();

        $categories = Kategorija::orderBy('ImeKategorija')->get();
        $types = TipProizvoda::orderBy('naziv_tip')->get();
        $brands = Brand::orderBy('name')->get();

        $defaultSifra = $this->generateRandomSifra();

        return view('admin.products.index', compact(
            'products', 'categories', 'types', 'brands', 'defaultSifra'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sifra' => ['required', 'string', 'max:50', 'unique:proizvod,sifra'],
            'Naziv' => ['required', 'string', 'max:100'],
            'KratkiOpis' => ['nullable', 'string'],
            'Opis' => ['nullable', 'string'],
            'Cijena' => ['required', 'numeric', 'min:0'],
            'DetaljniOpis' => ['nullable', 'string'],
            'kategorija' => ['required', 'exists:kategorija,id_kategorija'],
            'tip_proizvoda_id' => ['nullable', 'exists:tip_proizvoda,id_tip'],
            'StanjeNaSkladistu' => ['required', 'integer', 'min:0'],
            'slika' => ['nullable', 'image'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'socket_type' => ['nullable', 'string', 'max:50'],
            'ram_type' => ['nullable', 'string', 'max:20'],
            'form_factor' => ['nullable', 'string', 'max:20'],
            'wattage' => ['nullable', 'integer', 'min:1'],
            'tdp' => ['nullable', 'integer', 'min:1'],
        ]);

        $tip = $data['tip_proizvoda_id'] ? TipProizvoda::find($data['tip_proizvoda_id']) : null;

        if ($request->hasFile('slika')) {
            $file = $request->file('slika');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = 'uploads/products';

            if ($tip && $tip->konfigurator && $tip->slug) {
                $subFolder = $this->folderMap[$tip->slug] ?? $tip->slug;
                $folder = "uploads/pc-components/{$subFolder}";
            }

            Storage::disk('public')->putFileAs($folder, $file, $filename);
            $data['slika'] = "{$folder}/{$filename}";
        }

        DB::transaction(function () use ($data, $tip) {
            $product = Proizvod::create($data);

            if ($tip && $tip->konfigurator) {
                PcComponentSpec::create([
                    'proizvod_id'     => $product->Proizvod_ID,
                    'tip_proizvoda_id' => $tip->id_tip,
                    'socket_type'     => $data['socket_type'] ?? null,
                    'ram_type'        => $data['ram_type'] ?? null,
                    'form_factor'     => $data['form_factor'] ?? null,
                    'wattage'         => $data['wattage'] ?? null,
                    'tdp'             => $data['tdp'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je uspješno dodan.');
    }

    public function update(Request $request, Proizvod $product)
    {
        $data = $request->validate([
            'sifra' => [
                'required', 'string', 'max:50',
                'unique:proizvod,sifra,' . $product->Proizvod_ID . ',Proizvod_ID',
            ],
            'Naziv' => ['required', 'string', 'max:100'],
            'KratkiOpis' => ['nullable', 'string'],
            'Opis' => ['nullable', 'string'],
            'Cijena' => ['required', 'numeric', 'min:0'],
            'DetaljniOpis' => ['nullable', 'string'],
            'kategorija' => ['required', 'exists:kategorija,id_kategorija'],
            'tip_proizvoda_id' => ['nullable', 'exists:tip_proizvoda,id_tip'],
            'StanjeNaSkladistu' => ['required', 'integer', 'min:0'],
            'slika' => ['nullable', 'image'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'socket_type' => ['nullable', 'string', 'max:50'],
            'ram_type' => ['nullable', 'string', 'max:20'],
            'form_factor' => ['nullable', 'string', 'max:20'],
            'wattage' => ['nullable', 'integer', 'min:1'],
            'tdp' => ['nullable', 'integer', 'min:1'],
        ]);

        $tip = $data['tip_proizvoda_id'] ? TipProizvoda::find($data['tip_proizvoda_id']) : null;

        if ($request->hasFile('slika')) {
            if ($product->slika) {
                Storage::disk('public')->delete($product->slika);
            }

            $file = $request->file('slika');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = 'uploads/products';

            if ($tip && $tip->konfigurator && $tip->slug) {
                $subFolder = $this->folderMap[$tip->slug] ?? $tip->slug;
                $folder = "uploads/pc-components/{$subFolder}";
            }

            Storage::disk('public')->putFileAs($folder, $file, $filename);
            $data['slika'] = "{$folder}/{$filename}";
        }

        DB::transaction(function () use ($data, $tip, $product) {
            $product->update($data);

            if ($tip && $tip->konfigurator) {
                PcComponentSpec::updateOrCreate(
                    ['proizvod_id' => $product->Proizvod_ID],
                    [
                        'tip_proizvoda_id' => $tip->id_tip,
                        'socket_type'      => $data['socket_type'] ?? null,
                        'ram_type'         => $data['ram_type'] ?? null,
                        'form_factor'      => $data['form_factor'] ?? null,
                        'wattage'          => $data['wattage'] ?? null,
                        'tdp'              => $data['tdp'] ?? null,
                    ]
                );
            } else {
                $product->pcSpec()->delete();
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je ažuriran.');
    }

    public function destroy(Proizvod $product)
    {
        if ($product->slika) {
            Storage::disk('public')->delete($product->slika);
        }

        $product->pcSpec()->delete();
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je obrisan.');
    }

    private function generateRandomSifra(): string
    {
        do {
            $candidate = 'ART-' . random_int(0, 999999);
        } while (Proizvod::where('sifra', $candidate)->exists());

        return $candidate;
    }
}
