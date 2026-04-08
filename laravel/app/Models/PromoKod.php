<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoKod extends Model
{
    protected $table = 'promo_kodovi';
    protected $fillable = [
        'kod',
        'tip',
        'vrijednost',
        'vrijedi_od',
        'vrijedi_do',
        'max_koristenja',
        'koristenja',
        'minimalan_iznos',
        'aktivno',
    ];
}
