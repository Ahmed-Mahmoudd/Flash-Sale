<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'PlayStation 5',
            'price' => 25000,
            'total_stock' => 10,
            'reserved_stock' => 0,
        ]);

        Product::create([
            'name' => 'iPhone 16',
            'price' => 65000,
            'total_stock' => 5,
            'reserved_stock' => 0,
        ]);
    }
}
