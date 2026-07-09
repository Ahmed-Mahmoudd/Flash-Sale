<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HoldController;

Route::post('/holds', [HoldController::class, 'store']);
