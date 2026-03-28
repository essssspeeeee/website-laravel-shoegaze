<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini ada di dalam array $fillable
    protected $fillable = [
        'name',
        'price',
        'stock', // Ini yang tadi baru kita tambah
        'description',
        'images', // Ini untuk foto
    ];

    // Beritahu Laravel kalau stock dan images itu datanya berbentuk Array/JSON
    protected $casts = [
        'stock' => 'array',
        'images' => 'array',
    ];
}