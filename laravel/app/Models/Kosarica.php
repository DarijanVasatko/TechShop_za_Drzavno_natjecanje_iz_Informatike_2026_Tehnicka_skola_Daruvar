<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kosarica extends Model
{
    protected $table = 'kosarica';
    public $timestamps = false;

    protected $fillable = [
        'korisnik_id',
        'proizvod_id',
        'kolicina',
        'datum_dodavanja',
    ];

    

    public function proizvod()
{
    return $this->belongsTo(\App\Models\Proizvod::class, 'proizvod_id', 'Proizvod_ID');
}

}
