<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CartTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function no_se_puede_añadir_producto_sin_stock_al_carrito()
    {
        // 1. Crear rol y usuario
        $role = Role::create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        // 2. Crear producto sin stock
        $product = Product::factory()->create(['stock' => 0]);

        // 3. Intentar añadir al carrito
        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/cart/add', [
                             'product_id' => $product->id,
                             'quantity' => 1
                         ]);

        // 4. Validamos que devuelva error de lógica (422)
        $response->assertStatus(422);
    }

    #[Test]
    public function el_total_del_carrito_se_calcula_correctamente()
    {
        // 1. Crear rol y usuario
        $role = Role::create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        // 2. Crear producto con precio base
        $product = Product::factory()->create([
            'price' => 100.00,
            'stock' => 10
        ]);

        // 3. Añadimos 2 unidades (Subtotal: 200)
        $this->actingAs($user, 'api')
             ->postJson('/api/cart/add', [
                 'product_id' => $product->id,
                 'quantity' => 2
             ]);

        // 4. Consultamos el carrito
        $response = $this->actingAs($user, 'api')->getJson('/api/cart');

        /**
         * Lógica esperada:
         * Subtotal: 200
         * IVA (21%): 42
         * Total: 242
         */
        $response->assertStatus(200)
                 ->assertJsonFragment(['total' => 242]);
    }
}
