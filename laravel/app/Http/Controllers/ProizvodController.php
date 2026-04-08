<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Proizvod;
use App\Models\Kategorija;
use App\Models\TipProizvoda;
use App\Models\Brand;
use App\Models\Recenzija;
use App\Models\DetaljiNarudzbe;
use Illuminate\Support\Facades\Auth;

class ProizvodController extends Controller
{
    public function home()
    {
        $orderColumn = Schema::hasColumn('proizvod', 'created_at') ? 'created_at' : 'Proizvod_ID';
        $proizvodi = Proizvod::orderByDesc($orderColumn)->take(12)->get();
        $kategorije = Kategorija::all();

        return view('index', compact('proizvodi', 'kategorije'));
    }

    public function list(Request $request)
    {
        [$proizvodi, $kategorije, $specFilters, $brands, $types] = $this->queryProducts($request);
        return view('category', [
            'proizvodi'   => $proizvodi,
            'kategorije'  => $kategorije,
            'specFilters' => $specFilters,
            'brands'      => $brands,
            'types'       => $types,
            'categoryId'  => null,
        ]);
    }

    public function kategorija(Request $request, $id)
    {
        [$proizvodi, $kategorije, $specFilters, $brands, $types] = $this->queryProducts($request, (int)$id);
        return view('category', [
            'proizvodi'   => $proizvodi,
            'kategorije'  => $kategorije,
            'specFilters' => $specFilters,
            'brands'      => $brands,
            'types'       => $types,
            'categoryId'  => (int)$id,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        $categoryId = $request->integer('categoryId') ?: null;
        [$proizvodi, , , , ] = $this->queryProducts($request, $categoryId);

        $brandsQuery = Brand::whereIn('id', function ($q) use ($categoryId, $request) {
            $q->select('brand_id')->from('proizvod')->whereNotNull('brand_id');
            if ($categoryId) {
                $q->where('kategorija', $categoryId);
            }
            if ($request->filled('type')) {
                $q->where('tip_proizvoda_id', $request->input('type'));
            }
        })->orderBy('name')->get();

        return response()->json([
            'html'       => view('partials.products-grid', compact('proizvodi'))->render(),
            'pagination' => view('partials.products-pagination', compact('proizvodi'))->render(),
            'brands'     => $brandsQuery->map(fn($b) => ['id' => $b->id, 'name' => $b->name]),
        ]);
    }

    private function queryProducts(Request $request, ?int $categoryId = null): array
    {
        $query = Proizvod::with('brand');

        if (!is_null($categoryId)) {
            $query->where('kategorija', $categoryId);
        }

        if ($request->filled('type')) {
            $query->where('tip_proizvoda_id', $request->input('type'));
        }

        if ($search = $request->string('search')->toString()) {
            $query->where('Naziv', 'like', "%{$search}%");
        }

        if ($request->filled('max_price')) {
            $query->where('Cijena', '<=', $request->input('max_price'));
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        if ($request->filled('specs')) {
            foreach ($request->input('specs') as $column => $value) {
                if (!empty($value)) {
                    $query->whereExists(function ($q) use ($column, $value) {
                        $q->select(DB::raw(1))
                          ->from('pc_component_specs')
                          ->whereColumn('pc_component_specs.proizvod_id', 'proizvod.Proizvod_ID')
                          ->where($column, $value);
                    });
                }
            }
        }

        $hasCreatedAt = Schema::hasColumn('proizvod', 'created_at');
        $defaultOrderColumn = $hasCreatedAt ? 'created_at' : 'Proizvod_ID';

        switch ($request->string('sort')->toString()) {
            case 'price_asc':  $query->orderBy('Cijena', 'asc'); break;
            case 'price_desc': $query->orderBy('Cijena', 'desc'); break;
            case 'name_asc':   $query->orderBy('Naziv', 'asc'); break;
            case 'name_desc':  $query->orderBy('Naziv', 'desc'); break;
            default:           $query->orderBy($defaultOrderColumn, 'desc');
        }

        $proizvodi = $query->paginate(12)->withQueryString();
        $kategorije = Kategorija::all();

        $specFilters = [];
        if ($categoryId) {
            $specs = DB::table('pc_component_specs')
                ->whereIn('proizvod_id', Proizvod::where('kategorija', $categoryId)->pluck('Proizvod_ID'))
                ->select('socket_type', 'ram_type', 'form_factor')
                ->get();

            $specFilters = [
                'socket_type' => $specs->pluck('socket_type')->filter()->unique()->values(),
                'ram_type'    => $specs->pluck('ram_type')->filter()->unique()->values(),
                'form_factor' => $specs->pluck('form_factor')->filter()->unique()->values(),
            ];
        }

        $brands = Brand::whereIn('id', function ($q) use ($categoryId) {
            $q->select('brand_id')->from('proizvod')->whereNotNull('brand_id');
            if ($categoryId) {
                $q->where('kategorija', $categoryId);
            }
        })->orderBy('name')->get();

        $types = collect();
        if ($categoryId) {
            $types = TipProizvoda::where('kategorija_id', $categoryId)
                ->orderBy('naziv_tip')
                ->get();
        }

        return [$proizvodi, $kategorije, $specFilters, $brands, $types];
    }

    public function show($id)
    {
        $proizvod = Proizvod::with('brand')->findOrFail($id);

        $slicni = Proizvod::where('kategorija', $proizvod->kategorija)
            ->where('Proizvod_ID', '!=', $proizvod->Proizvod_ID)
            ->limit(4)
            ->get();

        $recenzije = Recenzija::with('user')
            ->where('proizvod_id', $id)
            ->where('odobrena', true)
            ->orderByDesc('created_at')
            ->get();

        $mozeRecenzirati = false;
        $postojecaRecenzija = null;

        if (Auth::check() && !Auth::user()->is_admin) {
            $postojecaRecenzija = Recenzija::where('user_id', Auth::id())
                ->where('proizvod_id', $id)
                ->first();

            if (!$postojecaRecenzija) {
                $mozeRecenzirati = DetaljiNarudzbe::where('Proizvod_ID', $id)
                    ->whereHas('narudzba', function ($query) {
                        $query->where('Kupac_ID', Auth::id())
                              ->whereIn('Status', ['Dostavljeno', 'Dovršena']);
                    })
                    ->exists();
            }
        }

        return view('product', compact('proizvod', 'slicni', 'recenzije', 'mozeRecenzirati', 'postojecaRecenzija'));
    }

    public function getByIds(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) return response()->json([]);

        $proizvodi = Proizvod::whereIn('Proizvod_ID', array_slice($ids, 0, 8))->get();
        $sorted = collect($ids)->map(fn($id) => $proizvodi->firstWhere('Proizvod_ID', $id))->filter();

        return response()->json($sorted->map(fn($p) => [
            'id'     => $p->Proizvod_ID,
            'naziv'  => $p->Naziv,
            'cijena' => number_format($p->Cijena, 2),
            'slika'  => $p->slika_url,
            'url'    => route('proizvod.show', $p->Proizvod_ID),
        ])->values());
    }
}