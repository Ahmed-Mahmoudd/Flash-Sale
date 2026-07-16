<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
  public function create(array $data): Order
  {
    return Order::create($data);
  }

  public function findByHoldId(int $holdId): ?Order
  {
    return Order::where('hold_id', $holdId)->first();
  }

  public function findByIdForUpdate(int $id): ?Order
  {
    return Order::lockForUpdate()->find($id);
  }

  public function save(Order $order): bool
  {
    return $order->save();
  }
}
