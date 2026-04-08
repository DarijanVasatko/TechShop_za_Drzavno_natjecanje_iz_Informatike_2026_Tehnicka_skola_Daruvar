<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Proizvod extends Model
{
    use HasFactory;

    protected $table = 'proizvod';
    protected $primaryKey = 'Proizvod_ID';
    public $timestamps = false;

    protected $fillable = [
        'sifra', 'Naziv', 'KratkiOpis', 'Opis', 'Cijena', 'DetaljniOpis', 'kategorija',
        'StanjeNaSkladistu', 'slika', 'tip_proizvoda_id', 'brand_id'
    ];

    protected $appends = ['slika_url'];

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class, 'kategorija', 'id_kategorija');
    }

    public function tip()
    {
        return $this->belongsTo(TipProizvoda::class, 'tip_proizvoda_id', 'id_tip');
    }

    public function kosarica()
    {
        return $this->hasMany(Kosarica::class, 'proizvod_id', 'Proizvod_ID');
    }

    public function detaljiNarudzbe()
    {
        return $this->hasMany(DetaljiNarudzbe::class, 'Proizvod_ID', 'Proizvod_ID');
    }

    public function pcSpec()
    {
        return $this->hasOne(PcComponentSpec::class, 'proizvod_id', 'Proizvod_ID');
    }

    public function getSlikaAttribute()
    {
        return $this->attributes['slika'] ?? $this->attributes['Slika'] ?? null;
    }

    public function getSlikaUrlAttribute()
    {
        if (!$this->slika) {
            return asset('img/no-image.svg');
        }

        if (str_starts_with($this->slika, 'http://') || str_starts_with($this->slika, 'https://')) {
            return $this->slika;
        }

        return Storage::disk('public')->url($this->slika);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function recenzije()
    {
        return $this->hasMany(Recenzija::class, 'proizvod_id', 'Proizvod_ID');
    }

    public function odobreneRecenzije()
    {
        return $this->hasMany(Recenzija::class, 'proizvod_id', 'Proizvod_ID')->where('odobrena', true);
    }
}
