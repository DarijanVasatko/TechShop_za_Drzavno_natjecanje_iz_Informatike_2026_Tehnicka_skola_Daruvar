<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'narudzba_id',
        'provider',
        'reference',
        'amount',
        'currency',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function narudzba()
    {
        return $this->belongsTo(Narudzba::class, 'narudzba_id', 'Narudzba_ID');
    }
}
