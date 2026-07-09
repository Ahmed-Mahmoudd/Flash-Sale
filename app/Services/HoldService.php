<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\HoldRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class HoldService
{
    public function __construct(
        private ProductRepository $productRepository,
        private HoldRepository $holdRepository
    ) {}

    public function createHold(int $productId, int $qty)
    {
        return DB::transaction(function () use ($productId, $qty) {
            $product = $this->productRepository->findByIdForUpdate($productId);

            if (!$product) {
                throw new \Exception('Product not found');
            }

            $availableStock = $product->total_stock - $product->reserved_stock;

            if ($availableStock < $qty) {
                throw new \Exception('Insufficient stock.');
            }

            $product->reserved_stock += $qty;
            $this->productRepository->save($product);

            //nsagel en fe hold gded
            $hold = $this->holdRepository->create([
                'product_id' => $product->id,
                'qty' => $qty,
                'status' => 'active',
                'expires_at' => Carbon::now()->addMinutes(2),
            ]);

            return $hold;
        });
    }
}
