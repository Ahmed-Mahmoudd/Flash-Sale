<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\HoldRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\WebhookEventRepository;

class WebhookService
{
  public function __construct(
    private HoldRepository $holdRepository,
    private OrderRepository $orderRepository,
    private ProductRepository $productRepository,
    private WebhookEventRepository $webhookEventRepository
  ) {}

  public function handleWebhook(
    string $idempotencyKey,
    int $orderId,
    string $paymentStatus
  ): void {

    DB::transaction(function () use (
      $idempotencyKey,
      $orderId,
      $paymentStatus
    ) {

      // Check Idempotency
      $existingEvent = $this->webhookEventRepository
        ->findByIdempotencyKey($idempotencyKey);

      if ($existingEvent) {
        return;
      }

      // Get Order
      $order = $this->orderRepository
        ->findByIdForUpdate($orderId);

      if (!$order) {
        throw new Exception('Order not found.');
      }

      if ($order->status !== 'pending_payment') {
        throw new \Exception('Order has already been processed.');
      }

      // Get Hold
      $hold = $order->hold;

      if (!$hold) {
        throw new Exception('Hold not found.');
      }

      // Get Product
      $product = $this->productRepository
        ->findByIdForUpdate($order->product_id);

      if (!$product) {
        throw new Exception('Product not found.');
      }

      // Payment Success
      if ($paymentStatus === 'paid') {

        $order->status = 'paid';
        $this->orderRepository->save($order);

        $hold->status = 'consumed';
        $this->holdRepository->save($hold);

        $product->total_stock -= $order->qty;
        if ($product->reserved_stock < $order->qty) {
          throw new \Exception('Reserved stock is invalid.');
        }

        $product->reserved_stock -= $order->qty;
        $this->productRepository->save($product);
      }
      // Payment Failed
      else {

        $order->status = 'cancelled';
        $this->orderRepository->save($order);

        $hold->status = 'released';
        $this->holdRepository->save($hold);

        $product->reserved_stock -= $order->qty;
        $this->productRepository->save($product);
      }

      // Save processed webhook
      $this->webhookEventRepository->create([
        'idempotency_key' => $idempotencyKey,
        'order_id' => $order->id,
        'status' => 'processed',
      ]);
    });
  }
}
