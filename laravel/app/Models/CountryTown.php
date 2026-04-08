<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryTown extends Model
{
    protected $table = 'country_town';
    protected $fillable = ['country_id', 'name'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
