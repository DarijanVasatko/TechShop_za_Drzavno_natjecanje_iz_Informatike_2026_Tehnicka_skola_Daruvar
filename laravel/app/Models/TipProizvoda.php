<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipProizvoda extends Model
{
    protected $table = 'tip_proizvoda';
    protected $primaryKey = 'id_tip';

    protected $fillable = [
        'naziv_tip',
        'kategorija_id',
        'konfigurator',
        'slug',
        'redoslijed',
        'ikona',
        'obavezan',
    ];

    protected $casts = [
        'konfigurator' => 'boolean',
        'obavezan'     => 'boolean',
    ];

    public function proizvodi(): HasMany
    {
        return $this->hasMany(Proizvod::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function kategorija(): BelongsTo
    {
        return $this->belongsTo(Kategorija::class, 'kategorija_id', 'id_kategorija');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(PcComponentSpec::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function configurationItems(): HasMany
    {
        return $this->hasMany(PcConfigurationItem::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function scopeKonfigurator($query)
    {
        return $query->where('konfigurator', true);
    }
}
