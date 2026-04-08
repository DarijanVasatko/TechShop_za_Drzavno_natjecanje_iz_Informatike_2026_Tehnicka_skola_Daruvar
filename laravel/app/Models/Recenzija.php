<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recenzija extends Model
{
    protected $table = 'recenzije';

    protected $fillable = [
        'user_id',
        'proizvod_id',
        'ocjena',
        'komentar',
        'odobrena',
    ];

    protected function casts(): array
    {
        return [
            'odobrena' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id', 'Proizvod_ID');
    }
}
