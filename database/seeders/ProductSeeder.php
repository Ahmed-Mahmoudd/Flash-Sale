<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Flash Sale Product',
            'price' => 100,
            'total_stock' => 10,
            'reserved_stock' => 0,
        ]);
    }
}
