<?php

namespace App\Repositories;

use App\Models\WebhookEvent;

class WebhookEventRepository
{
  public function create(array $data): WebhookEvent
  {
    return WebhookEvent::create($data);
  }

  public function findByIdempotencyKey(string $key): ?WebhookEvent
  {
    return WebhookEvent::where('idempotency_key', $key)->first();
  }
}
