<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    #[OA\Get(
        path: "/api/cart",
        summary: "Ver contenido del carrito y totales",
        security: [["bearerAuth" => []]],
        tags: ["Carrito"]
    )]
    #[OA\Response(response: 200, description: "Listado del carrito con IVA")]
    #[OA\Response(response: 401, description: "No autorizado")]
    public function index()
    {
        $userId = auth()->id();
        $cart = Cache::get("cart_{$userId}", []);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;

        return response()->json([
            'items' => array_values($cart),
            'subtotal' => round($subtotal, 2),
            'iva' => round($iva, 2),
            'total' => round($total, 2)
        ]);
    }

    #[OA\Post(
        path: "/api/cart/add",
        summary: "Añadir al carrito",
        security: [["bearerAuth" => []]],
        tags: ["Carrito"]
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "product_id", type: "integer"),
                new OA\Property(property: "quantity", type: "integer")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Producto añadido con éxito")]
    #[OA\Response(response: 422, description: "Error de validación o falta de stock")]
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'No hay suficiente stock'], 422);
        }

        $userId = auth()->id();
        $cart = Cache::get("cart_{$userId}", []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => (int)$request->quantity,
                'price' => (float)$product->price
            ];
        }

        Cache::put("cart_{$userId}", $cart, now()->addHours(24));

        return response()->json(['message' => 'Carrito actualizado', 'data' => array_values($cart)]);
    }

    #[OA\Post(
        path: "/api/cart/checkout",
        summary: "Finalizar compra",
        security: [["bearerAuth" => []]],
        tags: ["Carrito"]
    )]
    #[OA\Response(response: 200, description: "Compra procesada")]
    #[OA\Response(response: 400, description: "El carrito está vacío")]
    public function checkout()
    {
        $userId = auth()->id();
        $cart = Cache::get("cart_{$userId}", []);

        if (empty($cart)) {
            return response()->json(['error' => 'Vacío'], 400);
        }

        Cache::forget("cart_{$userId}");

        return response()->json(['message' => 'Compra procesada']);
    }
}
