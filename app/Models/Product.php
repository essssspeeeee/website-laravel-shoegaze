<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',      // json array: {"39":10, ...}
        'images',     // json array of stored paths
    ];

    protected $casts = [
        'stock' => 'array',
        'images' => 'array',
    ];
}