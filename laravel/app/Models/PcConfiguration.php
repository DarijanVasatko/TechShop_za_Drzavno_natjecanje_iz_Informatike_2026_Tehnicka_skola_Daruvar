<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PcConfiguration extends Model
{
    protected $table = 'pc_configurations';

    protected $fillable = [
        'user_id',
        'session_id',
        'naziv',
        'ukupna_cijena',
    ];

    protected $casts = [
        'ukupna_cijena' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PcConfigurationItem::class, 'configuration_id');
    }

    public function calculateTotalPrice(): float
    {
        $total = 0;
        foreach ($this->items()->get() as $item) {
            $total += $item->cijena_u_trenutku * $item->kolicina;
        }
        $this->ukupna_cijena = $total;
        $this->save();
        return $total;
    }

    public function addComponent(int $tipProizvodaId, int $proizvodId, float $cijena, int $kolicina = 1): PcConfigurationItem
    {
        $this->items()->where('tip_proizvoda_id', $tipProizvodaId)->delete();

        $item = $this->items()->create([
            'tip_proizvoda_id' => $tipProizvodaId,
            'proizvod_id' => $proizvodId,
            'cijena_u_trenutku' => $cijena,
            'kolicina' => $kolicina,
        ]);

        $this->calculateTotalPrice();

        return $item;
    }

    public function updateComponentQuantity(int $tipProizvodaId, int $kolicina): ?PcConfigurationItem
    {
        $item = $this->items()->where('tip_proizvoda_id', $tipProizvodaId)->first();
        if ($item) {
            $item->update(['kolicina' => max(1, $kolicina)]);
            $this->calculateTotalPrice();
        }
        return $item;
    }

    public function removeComponent(int $tipProizvodaId): bool
    {
        $deleted = $this->items()->where('tip_proizvoda_id', $tipProizvodaId)->delete();
        $this->calculateTotalPrice();
        return $deleted > 0;
    }

    public function removeIncompatibleComponents(): array
    {
        $items = $this->items()->with('proizvod.pcSpec.tipProizvoda', 'tipProizvoda')->get();
        $removed = [];

        do {
            $removedThisPass = [];

            foreach ($items as $item) {
                if (!$item->proizvod || !$item->proizvod->pcSpec) {
                    continue;
                }

                $itemOrder = $item->tipProizvoda->redoslijed ?? 0;

                foreach ($items as $other) {
                    if ($item->id === $other->id) continue;
                    if (!$other->proizvod || !$other->proizvod->pcSpec) continue;

                    if (!$item->proizvod->pcSpec->isCompatibleWith($other->proizvod->pcSpec)) {
                        $otherOrder = $other->tipProizvoda->redoslijed ?? 0;

                        if ($itemOrder > $otherOrder) {
                            $removedThisPass[] = $item;
                            break;
                        }
                    }
                }
            }

            foreach ($removedThisPass as $item) {
                $removed[] = $item->tipProizvoda->slug ?? 'unknown';
                $item->delete();
                $items = $items->reject(fn($i) => $i->id === $item->id);
            }
        } while (count($removedThisPass) > 0);

        if (count($removed) > 0) {
            $this->calculateTotalPrice();
        }

        return $removed;
    }

    public function getComponentByType(int $tipProizvodaId): ?PcConfigurationItem
    {
        return $this->items()->where('tip_proizvoda_id', $tipProizvodaId)->first();
    }

    public function isComplete(): bool
    {
        $requiredTypes = TipProizvoda::konfigurator()->where('obavezan', true)->pluck('id_tip');
        $selectedTypes = $this->items()->pluck('tip_proizvoda_id');

        return $requiredTypes->diff($selectedTypes)->isEmpty();
    }

    public function getTotalTdp(): int
    {
        $total = 0;
        foreach ($this->items()->with('proizvod.pcSpec')->get() as $item) {
            if ($item->proizvod && $item->proizvod->pcSpec) {
                $total += ($item->proizvod->pcSpec->tdp ?? 0) * $item->kolicina;
            }
        }
        return $total;
    }

    public function getRecommendedWattage(): int
    {
        $tdp = $this->getTotalTdp();
        return (int) ceil(($tdp + 50) * 1.2);
    }
}
