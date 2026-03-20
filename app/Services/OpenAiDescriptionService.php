<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiDescriptionService
{
    public function generateDescription(string $productName): string
    {
        // Obtenemos la clave de la configuración (que lee del .env)
        $apiKey = config('services.openrouter.key');

        // VALIDACIÓN DE SEGURIDAD:
        // Si no hay clave o es la de ejemplo, devolvemos un texto genérico profesional.
        if (!$apiKey || $apiKey === 'pon_aqui_tu_clave_de_openrouter') {
            return "Descripción premium para {$productName}: Calidad garantizada y diseño ergonómico para el mejor rendimiento.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer'  => 'http://localhost:8000',
                'X-Title'       => 'SDI Technical Test',
            ])->timeout(8)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.0-flash-lite-preview-02-05:free',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un experto en marketing. Crea una descripción de máximo 15 palabras.'],
                    ['role' => 'user', 'content' => "Producto: {$productName}"]
                ]
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? "Excelente opción: {$productName}";
            }

        } catch (\Exception $e) {
            Log::error("IA Service Error: " . $e->getMessage());
        }

        return "El nuevo {$productName} destaca por su versatilidad y acabado profesional.";
    }
}
