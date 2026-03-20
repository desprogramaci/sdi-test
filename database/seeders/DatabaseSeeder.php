<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role; // <--- ESTA LÍNEA ES LA QUE FALTA
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   public function run(): void
    {
        // 1. Crear Roles directamente con el modelo
        $adminRole = \App\Models\Role::create(['name' => 'admin']);
        $userRole = \App\Models\Role::create(['name' => 'user']);

        // 2. Crear Usuarios
        \App\Models\User::create([
            'name' => 'Admin SDi',
            'email' => 'admin@sdi.es',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);

        // 3. Llamar al Seeder de productos para crear productos
        $this->call(ProductSeeder::class);
    }
}