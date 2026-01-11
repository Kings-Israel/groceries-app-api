<?php

use App\Http\Controllers\AdminGroceryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroceryController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/groceries', [GroceryController::class, 'index']);

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Profile
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'myOrders']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/rate', [OrderController::class, 'rate']);
    Route::post('/groceries/check-availability', [GroceryController::class, 'checkAvailability']);
});

// Admin routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Orders management
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    // Groceries management
    Route::get('/groceries', [AdminGroceryController::class, 'index']);
    Route::post('/groceries', [AdminGroceryController::class, 'store']);
    Route::put('/groceries/{id}', [AdminGroceryController::class, 'update']);
    Route::delete('/groceries/{id}', [AdminGroceryController::class, 'destroy']);
});
