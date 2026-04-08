<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PcComponentSpec extends Model
{
    protected $table = 'pc_component_specs';

    protected $fillable = [
        'proizvod_id',
        'tip_proizvoda_id',
        'socket_type',
        'ram_type',
        'form_factor',
        'wattage',
        'tdp',
    ];

    public function proizvod(): BelongsTo
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id', 'Proizvod_ID');
    }

    public function tipProizvoda(): BelongsTo
    {
        return $this->belongsTo(TipProizvoda::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function isCompatibleWith(PcComponentSpec $other): bool
    {
        if ($this->socket_type && $other->socket_type) {
            if ($this->socket_type !== $other->socket_type) {
                return false;
            }
        }

        if ($this->ram_type && $other->ram_type) {
            if ($this->ram_type !== $other->ram_type) {
                return false;
            }
        }

        if ($this->form_factor && $other->form_factor) {
            return $this->isFormFactorCompatible($other);
        }

        return true;
    }

    protected function isFormFactorCompatible(PcComponentSpec $other): bool
    {
        $caseFits = [
            'ATX'  => ['ATX', 'mATX', 'ITX'],
            'mATX' => ['mATX', 'ITX'],
            'ITX'  => ['ITX'],
        ];

        $thisSlug  = $this->tipProizvoda->slug ?? '';
        $otherSlug = $other->tipProizvoda->slug ?? '';

        if ($thisSlug === 'kuciste') {
            $caseFF = $this->form_factor;
            $moboFF = $other->form_factor;
        } elseif ($otherSlug === 'kuciste') {
            $caseFF = $other->form_factor;
            $moboFF = $this->form_factor;
        } else {
            return $this->form_factor === $other->form_factor;
        }

        return isset($caseFits[$caseFF]) && in_array($moboFF, $caseFits[$caseFF]);
    }
}
