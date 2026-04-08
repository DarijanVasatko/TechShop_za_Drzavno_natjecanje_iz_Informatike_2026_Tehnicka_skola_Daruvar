<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NacinPlacanja extends Model
{
    use HasFactory;

    protected $table = 'nacin_placanja';
    protected $primaryKey = 'NacinPlacanja_ID';
    public $timestamps = true;

    protected $fillable = ['Opis'];

    
    public function narudzbe()
    {
        return $this->hasMany(Narudzba::class, 'NacinPlacanja_ID');
    }

    
    public function getNazivAttribute()
    {
        return $this->attributes['Opis'] ?? null;
    }
}
