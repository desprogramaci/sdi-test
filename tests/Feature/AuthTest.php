<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function usuario_no_autenticado_no_puede_ver_productos()
    {
        // Intentar acceder a una ruta protegida sin token
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    #[Test]
    public function usuario_puede_hacer_login_y_recibir_token()
    {
        // 1. Creamos el rol sin el campo 'slug' para evitar errores de columna inexistente
        $role = Role::create([
            'name' => 'Admin'
        ]);

        // 2. Creamos el usuario asignándole ese role_id mediante el factory
        $user = User::factory()->create([
            'email' => 'test@sdi.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        // 3. Intentamos el login enviando las credenciales
        $response = $this->postJson('/api/login', [
            'email' => 'test@sdi.com',
            'password' => 'password',
        ]);

        // 4. Verificamos que la respuesta sea exitosa y contenga el access_token de JWT
        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }
}
