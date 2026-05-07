<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\FreelancerController;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/services',      [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/freelancers',      [FreelancerController::class, 'index']);
Route::get('/freelancers/{id}', [FreelancerController::class, 'show']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    Route::put('/me',      [ProfileController::class, 'update']);
    Route::post('/me/avatar', [ProfileController::class, 'updateAvatar']);

    Route::apiResource('services',  ServiceController::class)->except(['index', 'show']);
    Route::apiResource('bookings',  BookingController::class);
    Route::apiResource('reviews',   ReviewController::class);
    Route::apiResource('portfolios', PortfolioController::class);

    Route::get('/messages/{booking}',  [MessageController::class, 'index']);
    Route::post('/messages',           [MessageController::class, 'store']);
});