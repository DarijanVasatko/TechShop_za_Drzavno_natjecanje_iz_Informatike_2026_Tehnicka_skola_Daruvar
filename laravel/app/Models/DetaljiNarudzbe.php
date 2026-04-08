<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetaljiNarudzbe extends Model
{
    use HasFactory;

    protected $table = 'detalji_narudzbe';
    protected $primaryKey = 'DetaljiNarudzbe_ID';
    public $timestamps = true; 

    protected $fillable = [
        'Narudzba_ID',
        'Proizvod_ID',
        'Kolicina',
        'cijena_po_komadu',
    ];

    
    public function narudzba()
    {
        return $this->belongsTo(Narudzba::class, 'Narudzba_ID');
    }

    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'Proizvod_ID');
    }

    
    public function getKolicinaAttribute()
    {
        return $this->attributes['Kolicina'] ?? null;
    }


    public function getCijenaAttribute()
    {
        if (!empty($this->attributes['cijena_po_komadu'])) {
            return $this->attributes['cijena_po_komadu'];
        }

        return optional($this->proizvod)->Cijena ?? 0;
    }
}
