<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kupac extends Model
{
    use HasFactory;

    protected $table = 'kupac';
    protected $primaryKey = 'Kupac_ID';
    public $timestamps = false;

    protected $fillable = [
        'Ime',
        'Prezime',
        'Email',
    ];

    
    public function narudzbe()
    {
        return $this->hasMany(Narudzba::class, 'Kupac_ID', 'Kupac_ID');
    }

    public function getImePrezimeAttribute(): string
    {
        return trim(($this->Ime ?? '') . ' ' . ($this->Prezime ?? ''));
    }
}
