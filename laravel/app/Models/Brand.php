<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relacija: Jedan brend ima mnogo proizvoda.
     */
    public function proizvodi()
    {
        return $this->hasMany(Proizvod::class, 'brand_id', 'id');
    }
}