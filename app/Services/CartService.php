<?php

namespace App\Services;

use App\Models\Product;
use Exception;

class CartService
{
    public function addProductToCart($user, Product $product, $quantity)
    {
        $price = (float) $product->price;
        $tax = $price * 0.21; // IVA 21%
        $subtotal = ($price + $tax) * $quantity;

        return [
            'product_name' => $product->name,
            'quantity'     => $quantity,
            'price_unit'   => number_format($price, 2) . '€',
            'tax_unit'     => number_format($tax, 2) . '€',
            'subtotal'     => number_format($subtotal, 2) . '€',
        ];
    }

    public function processCheckout($user)
    {
        // Simulamos que el carrito tiene productos que suman 120€ + IVA
        $totalSinIva = 120.00;
        $iva = $totalSinIva * 0.21;
        $totalConIva = $totalSinIva + $iva;

        $descuento = 0;
        // Lógica: Si supera 100€, 10% de descuento
        if ($totalConIva > 100) {
            $descuento = $totalConIva * 0.10;
            $totalConIva -= $descuento;
        }

        return [
            'customer' => $user->name,
            'total_bruto' => number_format($totalSinIva + $iva, 2) . '€',
            'descuento_aplicado' => number_format($descuento, 2) . '€',
            'total_neto' => number_format($totalConIva, 2) . '€',
            'status' => 'Pagado'
        ];
    }
}
