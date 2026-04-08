<?php

namespace App\Http\Controllers;

use App\Models\TipProizvoda;
use App\Models\PcComponentSpec;
use App\Models\PcConfiguration;
use App\Models\PcConfigurationItem;
use App\Models\Proizvod;
use App\Models\Kosarica;
use App\Services\AiRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class PcBuilderController extends Controller
{
    public function index()
    {
        $componentTypes = TipProizvoda::konfigurator()->orderBy('redoslijed')->get();
        $configuration = $this->getOrCreateConfiguration();

        return view('pc-builder.index', compact('componentTypes', 'configuration'));
    }

    public function newConfiguration()
    {
        session()->forget('pc_configuration_id');

        $config = PcConfiguration::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
        ]);

        session(['pc_configuration_id' => $config->id]);

        return redirect()->route('pc-builder.index')
            ->with('success', 'Nova konfiguracija je spremna!');
    }

    public function getStep(Request $request, $step)
    {
        $componentType = TipProizvoda::konfigurator()->where('slug', $step)->firstOrFail();
        $configuration = $this->getOrCreateConfiguration();

        $products = Proizvod::where('StanjeNaSkladistu', '>', 0)
            ->whereHas('pcSpec', function ($query) use ($componentType) {
                $query->where('tip_proizvoda_id', $componentType->id_tip);
            })->with('pcSpec')->get();

        $compatibleProducts = $this->filterCompatibleProducts($products, $configuration, $componentType);
        $currentSelection = $configuration->getComponentByType($componentType->id_tip);

        return response()->json([
            'componentType' => $componentType,
            'products' => $compatibleProducts,
            'currentSelection' => $currentSelection ? $currentSelection->load('proizvod') : null,
        ]);
    }

    public function getCompatibleProducts(Request $request, $typeId)
    {
        $componentType = TipProizvoda::konfigurator()->where('id_tip', $typeId)->firstOrFail();
        $configuration = $this->getOrCreateConfiguration();

        $products = Proizvod::where('StanjeNaSkladistu', '>', 0)
            ->whereHas('pcSpec', function ($query) use ($typeId) {
                $query->where('tip_proizvoda_id', $typeId);
            })->with(['pcSpec.tipProizvoda'])->get();

        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        if ($minPrice !== null) {
            $products = $products->where('Cijena', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $products = $products->where('Cijena', '<=', $maxPrice);
        }

        $compatibleProducts = $this->filterCompatibleProducts($products, $configuration, $componentType);

        return response()->json([
            'products' => $compatibleProducts->values(),
            'componentType' => $componentType,
        ]);
    }

    public function addComponent(Request $request)
    {
        $request->validate([
            'tip_proizvoda_id' => 'required|exists:tip_proizvoda,id_tip',
            'proizvod_id' => 'required|exists:proizvod,Proizvod_ID',
            'kolicina' => 'sometimes|integer|min:1|max:10',
        ]);

        $configuration = $this->getOrCreateConfiguration();
        $product = Proizvod::findOrFail($request->proizvod_id);

        if ($product->StanjeNaSkladistu <= 0) {
            return response()->json([
                'success' => false,
                'message' => "\"{$product->Naziv}\" trenutno nije dostupan.",
            ], 422);
        }

        $item = $configuration->addComponent(
            $request->tip_proizvoda_id,
            $request->proizvod_id,
            $product->Cijena,
            $request->input('kolicina', 1)
        );

        $removedSlugs = $configuration->removeIncompatibleComponents();

        return response()->json([
            'success' => true,
            'item' => $item->load(['proizvod', 'tipProizvoda']),
            'configuration' => $this->getConfigurationData($configuration),
            'removed_components' => $removedSlugs,
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'tip_proizvoda_id' => 'required|exists:tip_proizvoda,id_tip',
            'kolicina' => 'required|integer|min:1|max:10',
        ]);

        $configuration = $this->getOrCreateConfiguration();
        $item = $configuration->updateComponentQuantity(
            $request->tip_proizvoda_id,
            $request->kolicina
        );

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Komponenta nije pronađena.'], 404);
        }

        return response()->json([
            'success' => true,
            'configuration' => $this->getConfigurationData($configuration),
        ]);
    }

    public function removeComponent($typeId)
    {
        $configuration = $this->getOrCreateConfiguration();
        $configuration->removeComponent($typeId);

        return response()->json([
            'success' => true,
            'configuration' => $this->getConfigurationData($configuration),
        ]);
    }

    public function getConfiguration()
    {
        $configuration = $this->getOrCreateConfiguration();
        return response()->json($this->getConfigurationData($configuration));
    }

    public function addAllToCart(Request $request)
    {
        $configuration = $this->getOrCreateConfiguration();
        $items = $configuration->items()->with('proizvod')->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Konfiguracija je prazna.',
            ], 400);
        }

        $addedCount = 0;

        foreach ($items as $item) {
            $qty = $item->kolicina;

            if (Auth::check()) {
                $cartItem = DB::table('kosarica')
                    ->where('korisnik_id', Auth::id())
                    ->where('proizvod_id', $item->proizvod_id)
                    ->first();

                if ($cartItem) {
                    DB::table('kosarica')
                        ->where('id', $cartItem->id)
                        ->update(['kolicina' => $cartItem->kolicina + $qty]);
                } else {
                    DB::table('kosarica')->insert([
                        'korisnik_id' => Auth::id(),
                        'proizvod_id' => $item->proizvod_id,
                        'kolicina' => $qty,
                    ]);
                }
            } else {
                $cart = session('cart', []);
                $id = $item->proizvod_id;

                if (isset($cart[$id])) {
                    $cart[$id]['quantity'] += $qty;
                } else {
                    $cart[$id] = [
                        'product' => $item->proizvod,
                        'quantity' => $qty,
                    ];
                }
                session(['cart' => $cart]);
            }
            $addedCount += $qty;
        }

        if (Auth::check()) {
            $cartCount = DB::table('kosarica')->where('korisnik_id', Auth::id())->sum('kolicina');
        } else {
            $cartCount = collect(session('cart', []))->sum('quantity');
        }

        if ($request->boolean('sastavljanje')) {
            session(['pc_sastavljanje' => true]);
        } else {
            session()->forget('pc_sastavljanje');
        }

        return response()->json([
            'success' => true,
            'message' => "Dodano {$addedCount} proizvoda u košaricu!",
            'cartCount' => $cartCount,
        ]);
    }

    public function saveConfiguration(Request $request)
    {
        $request->validate([
            'naziv' => 'nullable|string|max:255',
        ]);

        $configuration = $this->getOrCreateConfiguration();
        $configuration->naziv = $request->input('naziv', 'Moja konfiguracija ' . now()->format('d.m.Y H:i'));
        $configuration->user_id = Auth::id();
        $configuration->save();

        return response()->json([
            'success' => true,
            'message' => 'Konfiguracija je spremljena!',
            'configuration' => $configuration,
        ]);
    }

    public function savedConfigurations()
    {
        $configurations = PcConfiguration::where('user_id', Auth::id())
            ->whereNotNull('naziv')
            ->with('items.proizvod', 'items.tipProizvoda')
            ->orderByDesc('updated_at')
            ->get();

        return view('pc-builder.saved', compact('configurations'));
    }

    public function loadConfiguration($id)
    {
        $configuration = PcConfiguration::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        session(['pc_configuration_id' => $configuration->id]);

        return redirect()->route('pc-builder.index')
            ->with('success', 'Konfiguracija je učitana!');
    }

    public function deleteConfiguration($id)
    {
        $configuration = PcConfiguration::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (session('pc_configuration_id') == $configuration->id) {
            session()->forget('pc_configuration_id');
        }

        $configuration->items()->delete();
        $configuration->delete();

        return redirect()->route('pc-builder.saved')
            ->with('success', 'Konfiguracija je obrisana.');
    }

    public function aiRecommendation(Request $request, AiRecommendationService $service)
    {
        $request->validate([
            'budget' => 'required|numeric|min:300|max:5000',
            'purpose' => 'required|in:gaming,office,content-creation',
            'free_text' => 'nullable|string|max:500',
        ]);

        $key = 'ai-recommendation:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, config('ai.max_requests_per_hour', 10))) {
            return response()->json([
                'success' => false,
                'message' => 'Previše zahtjeva. Pokušajte ponovno za nekoliko minuta.',
            ], 429);
        }
        RateLimiter::hit($key, 3600);

        $freeText = $request->input('free_text', '') ?? '';
        $useAi = $request->boolean('use_ai', true);

        if ($useAi && config('ai.anthropic_api_key')) {
            $result = $service->aiRecommendation(
                $request->budget,
                $request->purpose,
                $freeText
            );
        } else {
            $result = $service->ruleBasedRecommendation(
                $request->budget,
                $request->purpose
            );
        }

        return response()->json([
            'success' => true,
            'recommendation' => $result,
        ]);
    }

    public function applyRecommendation(Request $request)
    {
        $request->validate([
            'components' => 'required|array',
            'components.*.proizvod_id' => 'required|exists:proizvod,Proizvod_ID',
            'components.*.tip_proizvoda_id' => 'required|exists:tip_proizvoda,id_tip',
        ]);

        $configuration = $this->getOrCreateConfiguration();

        $configuration->items()->delete();

        foreach ($request->components as $component) {
            $product = Proizvod::findOrFail($component['proizvod_id']);
            $configuration->addComponent(
                $component['tip_proizvoda_id'],
                $component['proizvod_id'],
                $product->Cijena
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'AI preporuka je učitana u konfigurator!',
            'configuration' => $this->getConfigurationData($configuration),
        ]);
    }

    protected function getOrCreateConfiguration(): PcConfiguration
    {
        $configId = session('pc_configuration_id');

        if ($configId) {
            $config = PcConfiguration::find($configId);
            if ($config) {
                if (Auth::check() && !$config->user_id) {
                    $config->user_id = Auth::id();
                    $config->save();
                }
                return $config;
            }
        }

        if (Auth::check()) {
            $config = PcConfiguration::where('user_id', Auth::id())
                ->whereNull('naziv')
                ->first();
        } else {
            $config = PcConfiguration::where('session_id', session()->getId())
                ->whereNull('naziv')
                ->first();
        }

        if ($config) {
            session(['pc_configuration_id' => $config->id]);
            return $config;
        }

        $config = PcConfiguration::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
        ]);

        session(['pc_configuration_id' => $config->id]);

        return $config;
    }

    protected function filterCompatibleProducts($products, PcConfiguration $configuration, TipProizvoda $componentType)
    {
        $selectedItems = $configuration->items()->with('proizvod.pcSpec.tipProizvoda')->get();

        if ($selectedItems->isEmpty()) {
            return $products;
        }

        $currentOrder = $componentType->redoslijed;

        return $products->filter(function ($product) use ($selectedItems, $currentOrder) {
            if (!$product->pcSpec) {
                return false;
            }

            foreach ($selectedItems as $item) {
                if ($item->proizvod && $item->proizvod->pcSpec && $item->tipProizvoda) {
                    if ($item->tipProizvoda->redoslijed > $currentOrder) {
                        continue;
                    }
                    if (!$product->pcSpec->isCompatibleWith($item->proizvod->pcSpec)) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    protected function getConfigurationData(PcConfiguration $configuration): array
    {
        $configuration->load('items.proizvod.pcSpec', 'items.tipProizvoda');
        $componentTypes = TipProizvoda::konfigurator()->orderBy('redoslijed')->get();

        $items = [];
        foreach ($componentTypes as $type) {
            $item = $configuration->items->where('tip_proizvoda_id', $type->id_tip)->first();
            $items[$type->slug] = $item ? [
                'id' => $item->id,
                'component_type' => $type,
                'proizvod' => $item->proizvod,
                'cijena' => $item->cijena_u_trenutku,
                'kolicina' => $item->kolicina,
            ] : null;
        }

        return [
            'id' => $configuration->id,
            'items' => $items,
            'ukupna_cijena' => $configuration->ukupna_cijena,
            'is_complete' => $configuration->isComplete(),
            'recommended_wattage' => $configuration->getRecommendedWattage(),
            'total_tdp' => $configuration->getTotalTdp(),
        ];
    }
}
