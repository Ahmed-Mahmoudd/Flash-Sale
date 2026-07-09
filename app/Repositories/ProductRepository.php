<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    //to lock the product row for update to prevent race conditions
    public function findByIdForUpdate(int $id): ?Product
    {
        return Product::where('id', $id)
            ->lockForUpdate()
            ->first();
    }

    //to save the product after updating the quantity
    public function save(Product $product): bool
    {
        return $product->save();
    }
}
