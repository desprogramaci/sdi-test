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
        'stock',
        'ean_13'
    ];

    /**
     * Scope para filtrar por rango de precio.
     */
    public function scopeFilterByPrice($query, $min, $max)
    {
        return $query->when($min, fn($q) => $q->where('price', '>=', $min))
                     ->when($max, fn($q) => $q->where('price', '<=', $max));
    }

    /**
     * Scope para filtrar por código EAN.
     */
    public function scopeByEan($query, $ean)
    {
        return $query->when($ean, fn($q) => $q->where('ean_13', $ean));
    }
}
