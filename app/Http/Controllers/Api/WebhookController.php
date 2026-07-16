<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWebhookRequest;
use App\Services\WebhookService;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    public function __construct(
        private WebhookService $webhookService
    ) {}

    public function store(StoreWebhookRequest $request): JsonResponse
    {
        $this->webhookService->handleWebhook(
            $request->idempotency_key,
            $request->order_id,
            $request->payment_status
        );

        return response()->json([
            'message' => 'Webhook processed successfully.',
        ]);
    }
}
