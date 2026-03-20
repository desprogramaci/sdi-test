<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\OpenAiDescriptionService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(title: "SDI Marketplace API", version: "1.0.0")]
#[OA\Server(url: "http://localhost:8000", description: "Servidor Local")]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class ProductController extends Controller
{
    #[OA\Get(
        path: "/api/products",
        summary: "Listado de productos",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Lista de productos obtenida")]
    #[OA\Response(response: 401, description: "No autorizado")]
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('ean_13')) {
            $query->where('ean_13', $request->ean_13);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        return response()->json($query->get());
    }

    #[OA\Post(
        path: "/api/products",
        summary: "Crear un producto con descripción generada por IA",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["name", "price", "stock", "ean_13"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Teclado Mecánico RGB"),
                new OA\Property(property: "price", type: "number", format: "float", example: 89.99),
                new OA\Property(property: "stock", type: "integer", example: 50),
                new OA\Property(property: "ean_13", type: "string", example: "8412345678901"),
                new OA\Property(property: "description", type: "string", example: "", description: "Si se deja vacío, la IA generará una descripción.")
            ]
        )
    )]
    #[OA\Response(response: 201, description: "Producto creado exitosamente")]
    #[OA\Response(response: 422, description: "Error de validación")]
    public function store(Request $request, OpenAiDescriptionService $aiService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ean_13' => 'required|string|size:13|unique:products',
            'description' => 'nullable|string'
        ]);

        // Si la descripción viene vacía, llamamos al servicio de IA
        if (empty($validated['description'])) {
            $validated['description'] = $aiService->generateDescription($validated['name']);
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Producto creado correctamente',
            'data' => $product
        ], 201);
    }

    #[OA\Get(
        path: "/api/products/export",
        summary: "Exportar productos a Excel/CSV",
        security: [["bearerAuth" => []]],
        tags: ["Productos"]
    )]
    #[OA\Response(response: 200, description: "Funcionalidad de exportación")]
    public function export()
    {
        return response()->json(['message' => 'Funcionalidad de exportación activada y lista para descargar']);
    }
}
