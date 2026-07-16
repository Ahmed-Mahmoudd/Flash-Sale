<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HoldController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WebhookController;


Route::post('/holds', [HoldController::class, 'store']);

Route::post('/orders', [OrderController::class, 'store']);

Route::post('/webhook', [WebhookController::class, 'store']);
