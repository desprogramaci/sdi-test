<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Laptop Pro SDi',
            'description' => 'Potente laptop para desarrollo',
            'price' => 1200.00,
            'stock' => 10,
            'ean_13' => '1234567890123'
        ]);

        \App\Models\Product::create([
            'name' => 'Mouse Gaming',
            'description' => 'Ergonómico y rápido',
            'price' => 45.50,
            'stock' => 50,
            'ean_13' => '9876543210987'
        ]);
    }
}