<?php

use App\Http\Controllers\Api\DriverOrderController;

Route::middleware('auth:sanctum')->prefix('driver')->group(function () {
    Route::get('/orders/new',        [DriverOrderController::class, 'latestNewOrder']);
    Route::get('/orders',            [DriverOrderController::class, 'index']);
    Route::get('/orders/{id}',       [DriverOrderController::class, 'show']);
    Route::post('/orders/{id}/delivered',     [DriverOrderController::class, 'markDelivered']);
    Route::post('/orders/{id}/not-delivered', [DriverOrderController::class, 'markNotDelivered']);
});
