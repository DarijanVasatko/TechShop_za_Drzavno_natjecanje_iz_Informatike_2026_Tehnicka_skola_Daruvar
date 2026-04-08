<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Narudzba extends Model
{
    use HasFactory;

    protected $table = 'narudzba';
    protected $primaryKey = 'Narudzba_ID';
    public $timestamps = true;

     protected $fillable = [
        'Kupac_ID',
        'NacinPlacanja_ID',
        'Datum_narudzbe',
        'Ukupni_iznos',
        'Status',
        'Adresa_dostave',
        'potpis',
        'napomena',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'Kupac_ID', 'id');
    }
     public function detalji()
    {
        return $this->hasMany(DetaljiNarudzbe::class, 'Narudzba_ID');
    }

    public function nacinPlacanja()
    {
        return $this->belongsTo(NacinPlacanja::class, 'NacinPlacanja_ID');
    }

   
    public function getIdAttribute()
    {
        return $this->attributes['Narudzba_ID'] ?? null;
    }

    public function getUserIdAttribute()
    {
        return $this->attributes['Kupac_ID'] ?? null;
    }

    public function getUkupnaCijenaAttribute()
    {
        return $this->attributes['Ukupni_iznos'] ?? null;
    }

    public function getStatusAttribute()
    {
        return $this->attributes['Status'] ?? 'U obradi';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['Status'] = $value ?: 'U obradi';
    }
}
