<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PcConfigurationItem extends Model
{
    protected $table = 'pc_configuration_items';

    protected $fillable = [
        'configuration_id',
        'tip_proizvoda_id',
        'proizvod_id',
        'cijena_u_trenutku',
        'kolicina',
    ];

    protected $casts = [
        'cijena_u_trenutku' => 'decimal:2',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(PcConfiguration::class, 'configuration_id');
    }

    public function tipProizvoda(): BelongsTo
    {
        return $this->belongsTo(TipProizvoda::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function proizvod(): BelongsTo
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id', 'Proizvod_ID');
    }
}
