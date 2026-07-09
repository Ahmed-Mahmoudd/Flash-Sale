<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHoldRequest;
use App\Services\HoldService;
use Illuminate\Http\JsonResponse;

class HoldController extends Controller
{
    public function __construct(
        private HoldService $holdService
    ) {}

    public function store(StoreHoldRequest $request): JsonResponse
    {
        $hold = $this->holdService->createHold(
            $request->product_id,
            $request->qty
        );

        return response()->json([
            'message' => 'Hold created successfully.',
            'data' => $hold,
        ], 201);
    }
}
