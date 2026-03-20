<?php

namespace App\Contracts;

/**
 * Interface ProductDescriptionInterface
 * Define el contrato para los servicios de generación de contenido mediante IA.
 */
interface ProductDescriptionInterface
{
    /**
     * Genera una descripción comercial para un producto.
     *
     * @param string $productName
     * @return string
     */
    public function generateDescription(string $productName): string;
}
