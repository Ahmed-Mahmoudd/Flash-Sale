<?php

namespace App\Services;

use App\Repositories\HoldRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class OrderService
{
  public function __construct(
    private HoldRepository $holdRepository,
    private OrderRepository $orderRepository
  ) {}

  public function createOrder(int $holdId)
  {
    return DB::transaction(function () use ($holdId) {

      $hold = $this->holdRepository->findByIdForUpdate($holdId);

      if (!$hold) {
        throw new Exception('Hold not found.');
      }

      if ($hold->status !== 'active') {
        throw new Exception('Hold is no longer active.');
      }

      if ($hold->expires_at < Carbon::now()) {
        throw new Exception('Hold has expired.');
      }

      if ($this->orderRepository->findByHoldId($holdId)) {
        throw new Exception('Order already exists for this hold.');
      }

      $order = $this->orderRepository->create([
        'hold_id'    => $hold->id,
        'product_id' => $hold->product_id,
        'qty'        => $hold->qty,
        'status'     => 'pending_payment',
      ]);
      $hold->status = 'consumed';
      $this->holdRepository->save($hold);

      return $order;
    });
  }
}
