<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price'       => $this->faker->randomFloat(2, 5, 1000), // Precio entre 5 y 1000
            'stock'       => $this->faker->numberBetween(0, 50),    // Stock aleatorio
            'ean_13'      => $this->faker->ean13(),                // Sincronizado: ean_13
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }

    /**
     * Estado para productos sin stock (útil para el CartTest)
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
