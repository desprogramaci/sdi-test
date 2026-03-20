<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'ean_13'
    ];

    // Scopes para los filtros que pide el PDF
    public function scopeFilterByPrice($query, $min, $max)
    {
        return $query->when($min, fn($q) => $q->where('price', '>=', $min))
                     ->when($max, fn($q) => $q->where('price', '<=', $max));
    }

    public function scopeByEan($query, $ean)
    {
        return $query->when($ean, fn($q) => $q->where('ean_13', $ean));
    }
}
