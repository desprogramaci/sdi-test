<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SDI Marketplace API",
 *      description="Documentación de la prueba técnica SDI. Gestión de productos con IA y Carrito de compras.",
 *      @OA\Contact(
 *          email="admin@sdi.es"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8000",
 *      description="Servidor Local de Desarrollo"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Ingrese su token JWT (Bearer)",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * @OA\Get(
     *     path="/api/test",
     *     @OA\Response(response="200", description="Test")
     * )
     */
    public function test() {}
}