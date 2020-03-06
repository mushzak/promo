<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'promo',
        'sale_size',
        'max_count',
        'format',
        'use_count'
    ];
}
