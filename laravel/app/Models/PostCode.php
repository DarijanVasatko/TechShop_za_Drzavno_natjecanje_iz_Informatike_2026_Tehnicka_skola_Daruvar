<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{
    protected $table = 'post_codes';

    protected $fillable = [
        'city',
        'postal_code',
        'country',
    ];
}
