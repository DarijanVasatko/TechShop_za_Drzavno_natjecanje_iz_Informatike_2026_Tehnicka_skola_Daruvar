<?php

namespace App\Services;

use App\Models\Proizvod;
use App\Models\TipProizvoda;
use App\Models\PcComponentSpec;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiRecommendationService
{
    public function ruleBasedRecommendation(float $budget, string $purpose): array
    {
        $profiles = config('ai.budget_profiles');
        $profile = $profiles[$purpose] ?? $profiles['gaming'];

        $componentTypes = TipProizvoda::konfigurator()->orderBy('redoslijed')->get();
        $selected = [];
        $remainingBudget = $budget;

        foreach ($componentTypes as $type) {
            $allocation = $profile[$type->slug] ?? 0.10;
            $categoryBudget = $budget * $allocation;

            $products = Proizvod::whereHas('pcSpec', function ($q) use ($type) {
                $q->where('tip_proizvoda_id', $type->id_tip);
            })->with('pcSpec')
              ->where('Cijena', '<=', $categoryBudget)
              ->orderByDesc('Cijena') 
              ->get();

            $compatible = $this->filterCompatible($products, $selected);

            if ($compatible->isNotEmpty()) {
                $pick = $compatible->first();
                $selected[] = [
                    'tip_proizvoda_id' => $type->id_tip,
                    'slug' => $type->slug,
                    'naziv_tip' => $type->naziv_tip,
                    'proizvod_id' => $pick->Proizvod_ID,
                    'naziv' => $pick->Naziv,
                    'cijena' => (float) $pick->Cijena,
                    'kratki_opis' => $pick->KratkiOpis,
                    'slika_url' => $pick->slika_url,
                    'spec' => $pick->pcSpec,
                ];
                $remainingBudget -= $pick->Cijena;
            }
        }

        if ($remainingBudget > 20) {
            $selected = $this->tryUpgrades($selected, $remainingBudget, $budget, $profile);
        }

        $totalPrice = collect($selected)->sum('cijena');

        return [
            'components' => $selected,
            'total_price' => round($totalPrice, 2),
            'remaining_budget' => round($budget - $totalPrice, 2),
            'method' => 'rule-based',
            'purpose' => $purpose,
        ];
    }

    public function aiRecommendation(float $budget, string $purpose, string $freeText = ''): array
    {
        $apiKey = config('ai.anthropic_api_key');

        if (!$apiKey) {
            $result = $this->ruleBasedRecommendation($budget, $purpose);
            $result['method'] = 'rule-based (API ključ nije konfiguriran)';
            return $result;
        }

        $catalog = $this->buildCatalog();
        $prompt = $this->buildPrompt($catalog, $budget, $purpose, $freeText);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => config('ai.anthropic_model'),
                    'max_tokens' => 1024,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('AI Recommendation API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->ruleBasedRecommendation($budget, $purpose);
            }

            $content = $response->json('content.0.text', '');
            $recommendation = $this->parseAiResponse($content);

            if (empty($recommendation)) {
                return $this->ruleBasedRecommendation($budget, $purpose);
            }

            $validated = $this->validateRecommendation($recommendation, $budget);

            if (empty($validated['components'])) {
                return $this->ruleBasedRecommendation($budget, $purpose);
            }

            $validated['method'] = 'ai';
            $validated['purpose'] = $purpose;
            $validated['ai_explanation'] = $recommendation['explanation'] ?? '';

            return $validated;

        } catch (\Exception $e) {
            Log::warning('AI Recommendation exception', ['message' => $e->getMessage()]);
            return $this->ruleBasedRecommendation($budget, $purpose);
        }
    }

    private function buildCatalog(): array
    {
        $types = TipProizvoda::konfigurator()->orderBy('redoslijed')->get();
        $catalog = [];

        foreach ($types as $type) {
            $products = Proizvod::whereHas('pcSpec', function ($q) use ($type) {
                $q->where('tip_proizvoda_id', $type->id_tip);
            })->with('pcSpec')->orderBy('Cijena')->get();

            $catalog[$type->slug] = [
                'naziv' => $type->naziv_tip,
                'obavezan' => $type->obavezan,
                'products' => $products->map(function ($p) {
                    return [
                        'id' => $p->Proizvod_ID,
                        'naziv' => $p->Naziv,
                        'cijena' => (float) $p->Cijena,
                        'socket' => $p->pcSpec->socket_type,
                        'ram_type' => $p->pcSpec->ram_type,
                        'form_factor' => $p->pcSpec->form_factor,
                        'tdp' => $p->pcSpec->tdp,
                        'wattage' => $p->pcSpec->wattage,
                    ];
                })->values()->toArray(),
            ];
        }

        return $catalog;
    }

    private function buildPrompt(array $catalog, float $budget, string $purpose, string $freeText): string
    {
        $catalogJson = json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $purposeMap = [
            'gaming' => 'gaming PC (prioritet na GPU i CPU za igre)',
            'office' => 'uredski PC (pouzdanost, tihi rad, produktivnost)',
            'content-creation' => 'PC za kreiranje sadržaja (video editing, 3D rendering, streaming)',
        ];
        $purposeDesc = $purposeMap[$purpose] ?? $purpose;

        $userNote = $freeText ? "\nDodatne želje korisnika: {$freeText}" : '';

        return <<<PROMPT
Ti si stručnjak za sastavljanje PC konfiguracija u web-trgovini TechShop.

Korisnik želi: {$purposeDesc}
Budžet: {$budget} EUR{$userNote}

Evo kompletnog kataloga dostupnih komponenata (JSON):
{$catalogJson}

PRAVILA KOMPATIBILNOSTI:
- CPU i matična ploča MORAJU imati isti socket_type (npr. oba LGA1700 ili oba AM5)
- CPU hladnjak MORA imati isti socket_type kao CPU
- RAM MORA imati isti ram_type kao matična ploča (npr. oba DDR5)
- Kućište form_factor MORA biti jednako ili veće od matične ploče (ATX > mATX > ITX)
- Napajanje (wattage) MORA biti dovoljno za ukupni TDP svih komponenata (TDP × 1.2 + 50W margine)
- Ukupna cijena MORA biti unutar budžeta od {$budget} EUR

Odgovori ISKLJUČIVO u JSON formatu bez ikakvih dodatnih znakova ili markdown formatiranja:
{
  "components": [
    {"id": <Proizvod_ID>, "slug": "<slug kategorije>"}
  ],
  "explanation": "<kratko objašnjenje na hrvatskom zašto si odabrao ove komponente, max 2-3 rečenice. NE spominji nikakve cijene, iznose ni budžet u objašnjenju.>"
}

VAŽNO:
- Koristi SAMO ID-eve proizvoda iz kataloga. Ne izmišljaj ID-eve.
- Poštuj sva pravila kompatibilnosti.
- GPU i CPU hladnjak su opcionalni ali preporučeni za gaming.
- Za storage odaberi TOČNO JEDNU komponentu (SSD ili HDD, NE oboje). Konfigurator podržava samo jedan storage uređaj. Preferiraj SSD zbog brzine.
PROMPT;
    }

    private function parseAiResponse(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/^```json?\s*/i', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        $content = trim($content);

        $data = json_decode($content, true);

        if (!$data || !isset($data['components'])) {
            Log::warning('AI response parse failed', ['content' => $content]);
            return [];
        }

        return $data;
    }

    private function validateRecommendation(array $recommendation, float $budget): array
    {
        $components = [];
        $totalPrice = 0;

        foreach ($recommendation['components'] as $item) {
            $product = Proizvod::with('pcSpec.tipProizvoda')->find($item['id']);

            if (!$product || !$product->pcSpec) {
                continue;
            }

            $isCompatible = true;
            foreach ($components as $selected) {
                $selectedProduct = Proizvod::with('pcSpec')->find($selected['proizvod_id']);
                if ($selectedProduct && $selectedProduct->pcSpec) {
                    if (!$product->pcSpec->isCompatibleWith($selectedProduct->pcSpec)) {
                        $isCompatible = false;
                        break;
                    }
                }
            }

            if (!$isCompatible) {
                continue;
            }

            if ($totalPrice + $product->Cijena > $budget) {
                continue;
            }

            $type = $product->pcSpec->tipProizvoda;
            $components[] = [
                'tip_proizvoda_id' => $type->id_tip,
                'slug' => $type->slug,
                'naziv_tip' => $type->naziv_tip,
                'proizvod_id' => $product->Proizvod_ID,
                'naziv' => $product->Naziv,
                'cijena' => (float) $product->Cijena,
                'kratki_opis' => $product->KratkiOpis,
                'slika_url' => $product->slika_url,
                'spec' => $product->pcSpec,
            ];
            $totalPrice += $product->Cijena;
        }

        return [
            'components' => $components,
            'total_price' => round($totalPrice, 2),
            'remaining_budget' => round($budget - $totalPrice, 2),
        ];
    }

    private function filterCompatible($products, array $selected)
    {
        if (empty($selected)) {
            return $products;
        }

        return $products->filter(function ($product) use ($selected) {
            if (!$product->pcSpec) return false;

            foreach ($selected as $item) {
                $selectedProduct = Proizvod::with('pcSpec')->find($item['proizvod_id']);
                if ($selectedProduct && $selectedProduct->pcSpec) {
                    if (!$product->pcSpec->isCompatibleWith($selectedProduct->pcSpec)) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    private function tryUpgrades(array $selected, float $remaining, float $totalBudget, array $profile): array
    {
        $priorities = $profile;
        arsort($priorities);

        foreach ($priorities as $slug => $weight) {
            $idx = collect($selected)->search(fn($item) => $item['slug'] === $slug);

            if ($idx === false) continue;

            $current = $selected[$idx];
            $maxPrice = $current['cijena'] + $remaining;

            $type = TipProizvoda::konfigurator()->where('slug', $slug)->first();
            if (!$type) continue;

            $upgrade = Proizvod::whereHas('pcSpec', function ($q) use ($type) {
                $q->where('tip_proizvoda_id', $type->id_tip);
            })->with('pcSpec')
              ->where('Cijena', '>', $current['cijena'])
              ->where('Cijena', '<=', $maxPrice)
              ->orderByDesc('Cijena')
              ->first();

            if (!$upgrade) continue;

            $others = collect($selected)->except($idx)->values()->all();
            $compatible = $this->filterCompatible(collect([$upgrade]), $others);

            if ($compatible->isNotEmpty()) {
                $remaining -= ($upgrade->Cijena - $current['cijena']);
                $selected[$idx] = [
                    'tip_proizvoda_id' => $current['tip_proizvoda_id'],
                    'slug' => $slug,
                    'naziv_tip' => $current['naziv_tip'],
                    'proizvod_id' => $upgrade->Proizvod_ID,
                    'naziv' => $upgrade->Naziv,
                    'cijena' => (float) $upgrade->Cijena,
                    'kratki_opis' => $upgrade->KratkiOpis,
                    'slika_url' => $upgrade->slika_url,
                    'spec' => $upgrade->pcSpec,
                ];

                if ($remaining <= 20) break;
            }
        }

        return $selected;
    }
}
