<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
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
    #[OA\Response(response: 200, description: "Añadido")]
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|int|min:1']);

        $product = Product::find($request->product_id);
        $cart = Session::get('cart', []);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = ["name" => $product->name, "quantity" => $request->quantity, "price" => $product->price];
        }

        Session::put('cart', $cart);
        return response()->json(['message' => 'Carrito actualizado', 'data' => $cart]);
    }

    #[OA\Post(
        path: "/api/cart/checkout",
        summary: "Finalizar compra",
        security: [["bearerAuth" => []]],
        tags: ["Carrito"]
    )]
    #[OA\Response(response: 200, description: "Compra exitosa")]
    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) return response()->json(['error' => 'Vacío'], 400);

        Session::forget('cart');
        return response()->json(['message' => 'Compra procesada', 'resumen' => $cart]);
    }
}
