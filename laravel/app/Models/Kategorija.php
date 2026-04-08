<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategorija extends Model
{
    protected $table = 'kategorija';
    protected $primaryKey = 'id_kategorija';
    public $timestamps = false;

    protected $fillable = ['ImeKategorija'];

    public function proizvodi()
    {
        return $this->hasMany(Proizvod::class, 'kategorija', 'id_kategorija');
    }
}
