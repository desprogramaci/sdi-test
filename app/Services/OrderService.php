<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrderFromCart(Cart $cart, ?string $discountCode = null)
    {
        return DB::transaction(function () use ($cart, $discountCode) {
            // 1. Calcular totales usando el otro servicio
            $totals = $this->cartService->calculateTotals($cart, $discountCode);

            // 2. Crear la orden
            $order = Order::create([
                'user_id' => $cart->user_id,
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['descuento'],
                'tax_total' => $totals['impuestos'],
                'total' => $totals['total'],
                'status' => 'completed'
            ]);

            // 3. Mover items y restar stock
            foreach ($cart->items as $item) {
                $product = $item->product;

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stock insuficiente para: {$product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price
                ]);

                // Restar stock
                $product->decrement('stock', $item->quantity);
            }

            // 4. Vaciar carrito
            $cart->items()->delete();

            return $order;
        });
    }
}