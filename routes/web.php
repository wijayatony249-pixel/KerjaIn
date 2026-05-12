<?php

use App\Http\Controllers\Web\AuthController as WebAuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public
Route::get('/', fn() => Inertia::render('Landing'))->name('home');
Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn() => Inertia::render('Auth/Register'))->name('register');
Route::post('/register', [WebAuthController::class, 'register'])->name('register.post');

Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    
    // Services
    Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/layanan/kelola', [ServiceController::class, 'manage'])->name('services.manage');
    Route::get('/layanan/buat', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/layanan', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/layanan/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::post('/layanan/{service}', [ServiceController::class, 'update'])->name('services.update'); // Using POST for multipart update
    Route::get('/layanan/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::delete('/layanan/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    
    // Bookings
    Route::get('/booking', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    
    // Profile
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    
    // Portfolio
    Route::get('/portfolio', fn() => Inertia::render('Portfolio/Index'))->name('portfolio');
});