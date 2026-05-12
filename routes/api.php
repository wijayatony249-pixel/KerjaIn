<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FreelancerController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

// Public (outside middleware)
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);

// Protected routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // My services
    Route::apiResource('services', ServiceController::class);

    // Portfolios
    Route::get('/portfolios', [PortfolioController::class, 'index']);
    Route::post('/portfolios', [PortfolioController::class, 'store']);
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy']);

    // Bookings
    Route::apiResource('bookings', BookingController::class);

    // Reviews
    Route::apiResource('reviews', ReviewController::class);

    // Messages
    Route::get('/messages/{booking}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);

    // Freelancers
    Route::get('/freelancers', [FreelancerController::class, 'index']);
    Route::get('/freelancers/{user}', [FreelancerController::class, 'show']);
});